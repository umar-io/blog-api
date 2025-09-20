<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {

            $stringy_password = $request->password;

            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($stringy_password)
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['error' => 'User creation failed'], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => "Email / Password Incorrect"
                ], Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error Trying to login'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {

            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout successful'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Logout failed.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
