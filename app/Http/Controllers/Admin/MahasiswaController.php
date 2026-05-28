<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\MahasiswaApproved;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the mahasiswa.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::latest()->get();
        
        // Total peminjaman per judul buku (top 5)
        $peminjamanPerJudul = Buku::select('bukus.nama_buku as buku_judul', DB::raw('count(p.id) as total'))
            ->join('pinjamans as p', 'p.buku_id', '=', 'bukus.id')
            ->where('p.status', '!=', 'cancelled')
            ->groupBy('bukus.id', 'bukus.nama_buku')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Session login per hari selama 7 hari terakhir berdasarkan tanggal realtime aplikasi
        $timezone = config('app.timezone', 'Asia/Jakarta');
        $today = Carbon::now($timezone)->startOfDay();
        $startDate = $today->copy()->subDays(6);

        $sessionCounts = DB::table('sessions')
            ->where('last_activity', '>=', $startDate->copy()->timestamp)
            ->pluck('last_activity')
            ->map(function ($lastActivity) use ($timezone) {
                return Carbon::createFromTimestamp((int) $lastActivity, $timezone)->format('Y-m-d');
            })
            ->countBy();

        // Fill missing dates with 0 for chart, termasuk tanggal hari ini
        $login7Hari = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i)->format('Y-m-d');
            $login7Hari->push((object)[
                'date' => Carbon::parse($date, $timezone)->format('d M'),
                'total' => $sessionCounts->get($date, 0),
            ]);
        }
        
        return view('admin.mahasiswa.index', compact('mahasiswas', 'peminjamanPerJudul', 'login7Hari'));
    }

    /**
     * Store a newly created mahasiswa.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'nim'        => 'required|string|unique:mahasiswas,nim',
            'jurusan'    => 'required|in:Teknik Informatika (TI),Sistem Informatika (SI),Desain Komunikasi Visual (DKV),Teknik Sipil (TS)',
            'no_telepon' => 'required|string',
        ]);

        Mahasiswa::create([
            'nama'       => $request->nama,
            'nim'        => $request->nim,
            'jurusan'    => $request->jurusan,
            'no_telepon' => $request->no_telepon,
            'status'     => 'approved', // Admin-created mahasiswa are auto-approved
        ]);

        return response()->json(['success' => true, 'message' => 'Mahasiswa berhasil ditambahkan!']);
    }

    /**
     * Update the specified mahasiswa.
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $request->validate([
            'nama'       => 'required|string|max:100',
            'nim'        => 'required|string|unique:mahasiswas,nim,' . $id,
            'jurusan'    => 'required|in:Teknik Informatika (TI),Sistem Informatika (SI),Desain Komunikasi Visual (DKV),Teknik Sipil (TS)',
            'no_telepon' => 'required|string',
        ]);

        $mahasiswa->update($request->all());

        return response()->json(['success' => true, 'message' => 'Data berhasil diupdate!']);
    }

    /**
     * Remove the specified mahasiswa.
     */
    public function destroy($id)
    {
        Mahasiswa::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Mahasiswa berhasil dihapus!']);
    }

    /**
     * Approve a pending mahasiswa registration.
     */
    public function approve($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        
        // Generate referral token
        $mahasiswa->referral_token = $this->generateReferralToken();
        $mahasiswa->status = 'approved';
        $mahasiswa->save();

        // Send notification to the approved mahasiswa
        $mahasiswa->notify(new MahasiswaApproved($mahasiswa, $mahasiswa->referral_token));

        return response()->json([
            'success' => true, 
            'message' => 'Mahasiswa dengan NIM ' . $mahasiswa->nim . ' telah disetujui dan diverifikasi.',
            'referral_token' => $mahasiswa->referral_token
        ]);
    }

    /**
     * Generate unique 6-digit referral token
     */
    private function generateReferralToken()
    {
        do {
            $token = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (Mahasiswa::where('referral_token', $token)->exists());

        return $token;
    }

    /**
     * Reject a pending mahasiswa registration.
     */
    public function reject($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update(['status' => 'rejected']);

        return response()->json(['success' => true, 'message' => 'Mahasiswa ditolak!']);
    }

    /**
     * Process pending update request from mahasiswa
     */
    public function processUpdate(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);
        $action = $request->action;

        if ($action === 'approve') {
            if ($mahasiswa->pending_updates) {
                $mahasiswa->update($mahasiswa->pending_updates);
                $mahasiswa->update(['pending_updates' => null]);
            }
            return response()->json([
                'success' => true, 
                'message' => 'Update data mahasiswa berhasil disetujui.'
            ]);
        } else {
            $mahasiswa->update(['pending_updates' => null]);
            return response()->json([
                'success' => true, 
                'message' => 'Update data mahasiswa ditolak.'
            ]);
        }
    }

    public function resendEmail($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        
        // Check if mahasiswa is approved and has email
        if ($mahasiswa->status !== 'approved') {
            return response()->json([
                'success' => false, 
                'message' => 'Mahasiswa belum disetujui.'
            ], 400);
        }

        if (!$mahasiswa->email) {
            return response()->json([
                'success' => false, 
                'message' => 'Email mahasiswa tidak tersedia.'
            ], 400);
        }

        if (!$mahasiswa->referral_token) {
            return response()->json([
                'success' => false, 
                'message' => 'Token referral belum di-generate.'
            ], 400);
        }

        // Send notification
        try {
            $mahasiswa->notify(new MahasiswaApproved($mahasiswa, $mahasiswa->referral_token));
            
            return response()->json([
                'success' => true, 
                'message' => 'Email berhasil dikirim ulang ke ' . $mahasiswa->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }
}