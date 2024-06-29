<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\AnggotaKeluarga;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AnggotaKeluargaController extends Controller
{
    public function index()
    {
        $anggota = AnggotaKeluarga::where('user_id', Auth::user()->id)->get();
        return response()->json($anggota);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'provinsi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'hubungan_keluarga' => 'required|string|max:255',
        ]);

        $validatedData['user_id'] = Auth::user()->id;
        $anggota = AnggotaKeluarga::create($validatedData);
        return response()->json($anggota, 201);
    }

    public function show($id)
    {
        try {
            $anggota = AnggotaKeluarga::findOrFail($id);
            return response()->json($anggota);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Person not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'provinsi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'hubungan_keluarga' => 'required|string|max:255',
        ]);

        try {
            $anggota = AnggotaKeluarga::findOrFail($id);
            $anggota->update($validatedData);
            return response()->json($anggota);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Person not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $anggota = AnggotaKeluarga::findOrFail($id);
            $anggota->delete();
            return response()->json(['message' => 'berhasil menghapus anggota'], 200);
        } catch (\Exception $e) {
            Log::error("Person with ID $id not found.");
            return response()->json(['error' => 'Person not found'], 404);
        }
    }
}
