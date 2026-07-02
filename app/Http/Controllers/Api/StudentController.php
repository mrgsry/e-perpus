<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Get all students (paginated)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ]);

        $perPage = $request->input('per_page', 15);
        $students = Mahasiswa::paginate($perPage);

        return response()->json([
            'count' => $students->count(),
            'total' => $students->total(),
            'current_page' => $students->current_page(),
            'last_page' => $students->last_page(),
            'students' => $students->map(fn($student) => [
                'id' => $student->id,
                'nim' => $student->nim,
                'nama' => $student->nama,
                'email' => $student->email,
                'no_telepon' => $student->no_telepon,
                'jurusan' => $student->jurusan,
                'angkatan' => $student->angkatan,
                'status' => $student->status,
            ])->values(),
        ]);
    }

    /**
     * Create a new student
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:mahasiswas,email',
            'no_telepon' => 'required|string|max:20',
            'jurusan' => 'nullable|string|max:255',
            'angkatan' => 'nullable|integer',
            'status' => 'nullable|string|in:aktif,nonaktif',
        ]);

        try {
            $student = Mahasiswa::create([
                'nim' => $request->nim,
                'nama' => $request->nama,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'jurusan' => $request->jurusan,
                'angkatan' => $request->angkatan,
                'status' => $request->status ?? 'aktif',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mahasiswa berhasil dibuat',
                'student' => [
                    'id' => $student->id,
                    'nim' => $student->nim,
                    'nama' => $student->nama,
                    'email' => $student->email,
                    'no_telepon' => $student->no_telepon,
                    'jurusan' => $student->jurusan,
                    'angkatan' => $student->angkatan,
                    'status' => $student->status,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat mahasiswa: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get student by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $student = Mahasiswa::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'student' => [
                'id' => $student->id,
                'nim' => $student->nim,
                'nama' => $student->nama,
                'email' => $student->email,
                'no_telepon' => $student->no_telepon,
                'jurusan' => $student->jurusan,
                'angkatan' => $student->angkatan,
                'status' => $student->status,
                'created_at' => $student->created_at,
                'updated_at' => $student->updated_at,
            ],
        ]);
    }

    /**
     * Update student
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $student = Mahasiswa::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan',
            ], 404);
        }

        $request->validate([
            'nim' => 'nullable|string|unique:mahasiswas,nim,' . $id,
            'nama' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:mahasiswas,email,' . $id,
            'no_telepon' => 'nullable|string|max:20',
            'jurusan' => 'nullable|string|max:255',
            'angkatan' => 'nullable|integer',
            'status' => 'nullable|string|in:aktif,nonaktif',
        ]);

        try {
            $student->update($request->only(['nim', 'nama', 'email', 'no_telepon', 'jurusan', 'angkatan', 'status']));

            return response()->json([
                'success' => true,
                'message' => 'Mahasiswa berhasil diupdate',
                'student' => [
                    'id' => $student->id,
                    'nim' => $student->nim,
                    'nama' => $student->nama,
                    'email' => $student->email,
                    'no_telepon' => $student->no_telepon,
                    'jurusan' => $student->jurusan,
                    'angkatan' => $student->angkatan,
                    'status' => $student->status,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update mahasiswa: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete student
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $student = Mahasiswa::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan',
            ], 404);
        }

        try {
            $student->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mahasiswa berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus mahasiswa: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Search students by NIM or name
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $query = $request->input('query');
        $limit = $request->input('limit', 10);

        $students = Mahasiswa::where('nim', 'LIKE', '%' . $query . '%')
            ->orWhere('nama', 'LIKE', '%' . $query . '%')
            ->orWhere('email', 'LIKE', '%' . $query . '%')
            ->limit($limit)
            ->get();

        return response()->json([
            'found' => $students->isNotEmpty(),
            'count' => $students->count(),
            'students' => $students->map(fn($student) => [
                'id' => $student->id,
                'nim' => $student->nim,
                'nama' => $student->nama,
                'email' => $student->email,
                'no_telepon' => $student->no_telepon,
                'jurusan' => $student->jurusan,
                'angkatan' => $student->angkatan,
                'status' => $student->status,
            ])->values(),
        ]);
    }
}
