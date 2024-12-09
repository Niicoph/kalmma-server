<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    /**
     * Handles the login request
     * @return \Illuminate\Http\JsonResponse $response
     * @param \App\Http\Requests\LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            $token = JWTAuth::attempt($credentials);
            if ($token) {
                $encodedToken = base64_encode($token);
                $response = response()->json(['message' => 'Logged in successfully'])
                    ->cookie('sessionId', $encodedToken, 0, '/', null, true, true);
            } else {
                $response = response()->json(['error' => 'Credentials invalid'], 401);
            }
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            $response = response()->json(['error' => 'Login failed'], 500);
        }
        return $response;
    }


    /**
     * Handles the registration request
     * @param \App\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse $response
     */
    public function register(RegisterRequest $request)
    {
        $credentials = $request->only('email', 'password', 'name');
        try {
            $user = User::create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
            ]);

            $response = response()->json(['message' => 'User registered successfully'], 201);
        } catch (\Exception $e) {
            $response = response()->json(['error' => 'Registration failed'], 500);
        }
        return $response;
    }

    /**
     * Handles the logout request
     * @return \Illuminate\Http\JsonResponse $response
     */
    public function logout(Request $request)
    {
        try {
            $cookie = $request->cookie('sessionId');
            if (!$cookie) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } else {
                $decodedToken = base64_decode($cookie);
                $auth = JWTAuth::setToken($decodedToken)->authenticate();
                if ($auth) {
                    JWTAuth::invalidate();
                    $response = response()->json(['message' => 'Logged out successfully']);
                } else {
                    $response = response()->json(['error' => 'Unauthorized'], 401);
                }
            }
        } catch (JWTException $e) {
            $response = response()->json(['error' => 'Logout failed'], 500);
        }
        return $response;
    }

    public function refreshToken()
    {
        try {
            $refreshedToken = JWTAuth::refresh();
            $encodedToken = base64_encode($refreshedToken);
            $response = response()->json(['message' => 'Token refreshed successfully'])
                ->cookie('sessionId', $encodedToken, 0, '/', null, true, true);
        } catch (\Exception $e) {
            $response = response()->json(['error' => 'Token refresh failed'], 500);
        }
        return $response;
    }

    public function isAuth(Request $request)
    {
        try {
            $cookie = $request->cookie('sessionId');
            if (!$cookie) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } else {
                $decodedToken = base64_decode($cookie);
                $auth = JWTAuth::setToken($decodedToken)->authenticate();
                return $auth
                    ? response()->json(['message' => 'Authenticated'])
                    : response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'token error'], 401);
        }
    }
}
