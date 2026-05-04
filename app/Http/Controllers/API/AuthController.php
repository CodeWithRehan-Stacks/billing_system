<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!Auth::attempt($credentials)) {
                Log::warning('Login failed', ['email' => $request->email]);

                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = Auth::user(); 

            $token = $user->createToken('admin-token')->plainTextToken;

            Log::info('User logged in', ['user_id' => $user->id]);

            return response()->json([
                'message' => 'Logged in successfully',
                'user' => $user,
                'token' => $token
            ]);

        } catch (Throwable $e) {
            
            Log::error('Login error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage() // remove in production
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logged out successfully'
            ]);

        } catch (Throwable $e) {
            Log::error('Logout error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Logout failed'
            ], 500);
        }
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}