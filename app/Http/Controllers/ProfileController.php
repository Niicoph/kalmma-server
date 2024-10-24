<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;



class ProfileController extends Controller {
     

    /**
     * Display information about personal profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function show() {
        $user = User::find(auth()->user()->id);
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado',
            ], 404);
        } else {
            try {
                return response()->json([
                    'message' => 'Perfil obtenido correctamente',
                    'perfil' => $user,
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Error inesperado al obtener el perfil',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
    
    /**
     * Update the authenticated user's profile.
     * @param string $id
     * @return void
     */
    public function update(string $id, Request $request) {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|min:3|max:40',
            'email' => 'sometimes|string|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6|max:20',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        if (auth()->user()->id != $id) {
            return response()->json([
                'message' => 'No tienes permiso para actualizar este perfil',
            ], 403);
        }
    
        $user = User::find($id);
    
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado',
            ], 404);
        }
        $updatedFields = [];
        if (array_key_exists('name', $validatedData)) {
            $updatedFields[] = 'nombre';
        }
    
        if (array_key_exists('email', $validatedData)) {
            $updatedFields[] = 'email';
        }
    
        if (array_key_exists('password', $validatedData)) {
            $validatedData['password'] = bcrypt($validatedData['password']);
            $updatedFields[] = 'contraseña';
        }
    
        if (array_key_exists('avatar', $validatedData)) {
            $avatarName = hash('sha256', time() . $request->avatar->getClientOriginalName()) . '.' . $request->avatar->extension();
            $request->avatar->storeAs('avatars', $avatarName); 
            $validatedData['avatar'] = $avatarName;
            $updatedFields[] = 'avatar';
        }
    
        if (!empty($updatedFields)) {
            try {
                $user->update($validatedData); 
    
                $message = implode(', ', $updatedFields) . " actualizado correctamente.";
                return response()->json([
                    'message' => $message,
                    'perfil' => $user,
                ], 200);
    
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Error inesperado al actualizar el perfil',
                    'error' => $e->getMessage(),
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'No se proporcionaron campos válidos para actualizar',
            ], 400);
        }
    }
    
    
}
