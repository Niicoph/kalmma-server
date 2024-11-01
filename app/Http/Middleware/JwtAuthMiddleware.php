<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        try {
            if (!$request->hasCookie('sessionId')) {
                return response()->json(['error' => 'Token not provided. Please log in to access this resource.'], 401); 
            }

            $token = $request->cookie('sessionId'); 
            $decodedToken = base64_decode($token);
            $user = JWTAuth::setToken($decodedToken)->authenticate(); 

            if (!$user) {
                return response()->json(['error' => 'Token is invalid. Please log in again.'], 401); 
            }

            $request->attributes->set('user', $user);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token authentication failed'], 500);
        }

        return $next($request); 
    }
}
