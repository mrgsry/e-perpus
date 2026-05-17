<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Notifications\MahasiswaApproved;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the mahasiswa.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::latest()->get();
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Store a newly created mahasiswa.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'nim'        => 'required|string|unique:mahasiswas,nim',
            'jurusan'    => 'required|string',
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
            'jurusan'    => 'required|string',
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
     * Resend approval email to mahasiswa.
     */
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
