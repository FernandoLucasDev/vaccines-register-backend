<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse 
    {
        try
        {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid login credentials'], 401);
            }
    
            $user = User::where('email', $request->email)->firstOrFail();
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'token' => $token
            ], 200);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => 'Login error: ' . $e->getMessage()], 500);
        }
    }

    public function logout(Request $request): JsonResponse 
    {
        try
        {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logout successful'], 200);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => 'Logout error: ' . $e->getMessage()], 500);
        }
    }

    public function me(Request $request): JsonResponse 
    {
        return response()->json($request->user());
    }
}
