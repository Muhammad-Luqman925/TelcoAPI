<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menangani permintaan login untuk Admin dan Staff.
     */
    public function login(Request $request)
    {
        // 1. Validasi input: email dan password wajib diisi
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 3. Cek apakah user ada DAN password-nya benar
        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Jika salah, kirim error "Unauthorized"
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // 4. [PENTING] Cek Role: Hanya 'admin' atau 'staff' yang boleh login di sini
        if ($user->role !== 'admin' && $user->role !== 'staff') {
            throw ValidationException::withMessages([
                'email' => ['Anda tidak memiliki hak akses untuk login.'],
            ]);
        }

        // 5. Jika semua berhasil: Buat API token baru
        // Nama token sengaja dibuat unik untuk debugging
        $token = $user->createToken('api-token-'.$user->email)->plainTextToken;

        // 6. Kirim balasan token dan data user
        return response()->json([
            'message' => 'Login berhasil',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token,
        ], 200);
    }

    /**
     * Menangani permintaan logout.
     */
    public function logout(Request $request)
    {   
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $request->user()->currentAccessToken();

        if (!$token || ! method_exists($token, 'delete')) {
            return response()->json([
                'message' => 'Logout gagal: Tidak dapat menemukan token yang valid untuk dihapus.',
                'debug_info' => [
                    'token_type' => gettype($token),
                    'is_object' => is_object($token),
                ]
            ], 500);
        }

        $token->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ], 200);
    }

    /**
     * Menangani permintaan refresh token.
     * Endpoint ini harus diproteksi oleh auth:sanctum.
     */
    public function refresh(Request $request)
    {
        // Ambil user yang sedang login
        $user = $request->user();
        $currentToken = $user->currentAccessToken();

        if (!$currentToken || ! method_exists($currentToken, 'delete')) {
            return response()->json([
                'message' => 'Refresh gagal: Tidak dapat menemukan token yang valid untuk dihapus.',
                'debug_info' => [
                    'token_type' => gettype($currentToken),
                    'is_object' => is_object($currentToken),
                ]
            ], 500);
        }

        /** @var \Laravel\Sanctum\PersonalAccessToken $currentToken */
        $currentToken->delete();

        // 2. Buat token BARU
        $newToken = $user->createToken('api-token-'.$user->email)->plainTextToken;

        // 3. Kirim balasan token baru
        return response()->json([
            'message' => 'Token berhasil di-refresh',
            'token' => $newToken,
        ], 200);
    }
}