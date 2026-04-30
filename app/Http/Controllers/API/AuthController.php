<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    /**
     * REGISTER
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name'    => 'required|string|max:255',
                'last_name'     => 'required|string|max:255',
                'user_name'     => 'required|string|unique:users,user_name',
                'email'         => 'required|email|unique:users,email',
                'password'      => 'required|min:6|confirmed',
                'date_of_birth' => 'nullable|date',
            ]);

            $user = User::create([
                'first_name'    => $validated['first_name'],
                'last_name'     => $validated['last_name'],
                'user_name'     => $validated['user_name'],
                'email'         => $validated['email'],
                'password'      => Hash::make($validated['password']),
                'date_of_birth' => $validated['date_of_birth'] ?? null,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully',
                'user'    => $user,
                'token'   => $token
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            Log::error('Registration database error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Registration failed due to a database error',
                'error'   => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        } catch (Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An unexpected error occurred during registration',
                'error'   => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * LOGIN
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                    'errors'  => ['email' => ['The provided credentials do not match our records.']]
                ], 401);
            }

            // Revoke previous tokens if you want single session per user (optional)
            // $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user'    => $user,
                'token'   => $token
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during login',
                'error'   => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Revoke the current access token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);
        } catch (Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during logout',
                'error'   => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            return response()->json([
                'user' => $request->user()
            ], 200);
        } catch (\Exception $e) {
            Log::error('Profile fetch error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unable to fetch profile',
            ], 500);
        }
    }

    /**
     * UPDATE PROFILE
     */
    public function update(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], 401);
            }

            $rules = [
                'first_name'    => 'sometimes|string|max:255',
                'last_name'     => 'sometimes|string|max:255',
                'user_name'     => 'sometimes|string|unique:users,user_name,' . $user->id,
                'email'         => 'sometimes|email|unique:users,email,' . $user->id,
                'password'      => 'sometimes|min:6',
                'date_of_birth' => 'nullable|date',
            ];

            $validated = $request->validate($rules);

            $updateData = [
                'first_name'    => $validated['first_name'] ?? $user->first_name,
                'last_name'     => $validated['last_name'] ?? $user->last_name,
                'user_name'     => $validated['user_name'] ?? $user->user_name,
                'email'         => $validated['email'] ?? $user->email,
                'date_of_birth' => $validated['date_of_birth'] ?? $user->date_of_birth,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Reload user to get fresh data
            $user->refresh();

            return response()->json([
                'message' => 'Profile updated successfully',
                'user'    => $user
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            Log::error('Profile update DB error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Update failed due to a database error',
                'error'   => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        } catch (Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An unexpected error occurred while updating profile',
                'error'   => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
