<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    // HAPUS SELURUH METHOD __construct() DARI SINI

    /**
     * Menangani permintaan login dari pengguna.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Kredensial tidak valid'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Menangani pendaftaran pengguna baru.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::guard('api')->login($user);

        return response()->json([
            'message' => 'Pengguna berhasil dibuat!',
            'user' => $user,
            'token_details' => $this->getTokenDetails($token)
        ], 201);
    }

    /**
     * Menangani permintaan logout dari pengguna.
     */
    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Berhasil logout']);
    }

    /**
     * Mendapatkan detail pengguna yang sedang terautentikasi.
     */
    public function me(): JsonResponse
    {
        return response()->json(Auth::guard('api')->user());
    }

    /**
     * Memperbarui token yang sudah ada.
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(Auth::guard('api')->refresh());
    }

    /**
     * Helper method untuk membuat struktur response token.
     */
    protected function getTokenDetails(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in_seconds' => auth('api')->factory()->getTTL() * 60
        ];
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json($this->getTokenDetails($token));
    }
}
