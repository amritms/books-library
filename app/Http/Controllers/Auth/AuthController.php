<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = auth()->user()->createToken($request->device_name ?? 'Personal Access Token')->plainTextToken;

        return response()->json([
            'message' => 'Successfully Logged in.',
            'data' => ['token' => $token]
        ], Response::HTTP_OK);
    }

    public function Logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully Logged out.'
        ], Response::HTTP_RESET_CONTENT);
    }
}
