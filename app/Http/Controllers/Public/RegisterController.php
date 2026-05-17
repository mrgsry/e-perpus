<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create()
    {
        return view('publik.register');
    }

    /**
     * Handle the registration submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'nim'        => 'required|string|unique:mahasiswas,nim',
            'jurusan'    => 'required|string',
            'no_telepon' => 'nullable|string|max:255',
            'email'      => 'required|string|email|unique:mahasiswas,email',
        ]);

        Mahasiswa::create([
            'nama'       => $request->nama,
            'nim'        => $request->nim,
            'jurusan'    => $request->jurusan,
            'no_telepon' => $request->no_telepon,
            'email'      => $request->email,
            // status defaults to 'pending' via migration default
        ]);

        // Return JSON response for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil, menunggu persetujuan admin.'
            ]);
        }

        return redirect()->route('publik.register.form')
                         ->with('success', 'Pendaftaran berhasil, menunggu persetujuan admin.');
    }
}