<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'tgl_lahir' => 'required|date',
            'title_pasien' => 'required|in:nn,tn',
            'status_kawin' => 'required|in:belum,sudah',
            'tempat_lahir' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki,perempuan',
            'alamat' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'foto_ktp' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:255|unique:users',
            'pekerjaan' => 'required|string|max:255',
            'pendidikan' => 'required|string|max:255',
            'agama' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:255|unique:users',
            'pelayanan' => 'required|in:sudah,belum',
            'nama_keluarga' => 'required|string|max:255',
            'no_telepon_keluarga' => 'required|string|max:255|unique:users',
            'alamat_keluarga' => 'required|string|max:255',
            'hubungan_keluarga' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);
        // $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json(['user' => $user], 200);
    }

    // Login
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($data)) {
            $user = Auth::user();
            $token = $user->createToken('LaravelAuthApp')->plainTextToken;
            return response()->json([
                'success' => true,
                'user' => $user,
                'access_token' => $token,
                'message' => 'Login successful'
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    // Logout
    public function logout(Request $request)
    {
        try {
            // Get the token that the user wants to revoke
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'user not login'], 401);
        }
    }
}
