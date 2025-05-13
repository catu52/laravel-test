<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Create a new user
        $user = User::create($request->toArray());
        if($user)
        {
            // Get access token
            $token = $user->createToken('auth_token')->accessToken;
            //Return response
            return response()->json(['access_token' => $token, 'token_type' => 'Bearer'], 201);
        }
        // If user creation fails, return an error response
        return response()->json(['message' => 'User registration failed'], 500);
        
    }

    /**
     * Handle an incoming login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }
        // Get the authenticated user
        $user = $request->user();
        // Revoke all previous tokens
        $user->tokens()->delete();
        // Generate a new access token
        $token = $user->createToken('auth_token')->accessToken;
        // Return the access token in the response
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer'], 200);
    }
}
