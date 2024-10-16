<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller {    
    /**
     * Handles the login request
     * @return \Illuminate\Http\JsonResponse $response
     * @param \App\Http\Requests\LoginRequest $request
     */
    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');
        try {
            $token = JWTAuth::attempt($credentials);
            if ($token) {
                $response = response()->json(['message' => 'Logged in successfully'])->cookie('token', $token, 60, '/', null, true, true);
            } else {
                $response = response()->json(['error' => 'Credentials invalid'], 401);
            }
        } catch (\Exception $e) {
            $response = response()->json(['error' => 'Login failed'], 500);
        }
        return $response;
    }
    /**
     * Handles the registration request
     * @param \App\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse $response
     */
    public function register(RegisterRequest $request) {
        $credentials = $request->only('email', 'password','name');
        try {
            $user = User::create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
            ]);

            $response = response()->json([
                'message' => 'User registered successfully'], 201);
        } catch (\Exception $e) {
            $response = response()->json(['error' => 'Registration failed'], 500);
        }
        return $response;
    }
    /**
     * Handles the logout request
     */
    public function logout() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user) {
                JWTAuth::invalidate(JWTAuth::getToken());
                $response = response()->json(['message' => 'Logged out successfully'])->cookie('token', '', 1, '/', null, true, true);
            } else {
                $response = response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch(JWTException $e) {
            $response = response()->json(['error' => 'Logout failed'], 500);
        }
        return $response;
    }
     

}
