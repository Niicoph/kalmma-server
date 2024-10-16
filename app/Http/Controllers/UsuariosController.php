<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UsuariosController extends Controller {
    

    /**
     * Display a listing of users 
     * @return void
     */
    public function index() {
        try {
            $usuarios = User::all();
            if (!$usuarios) {
                return response()->json(['message' => 'No se encontraron usuarios'], 404);
            } else {
                return response()->json($usuarios, 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al obtener los usuarios'], 500);
        }
    }

    /**
     * Store a newly created user in storage
     * @param RegisterRequest $request
     * @return void
     */

     public function store(RegisterRequest $request) {
        $this->authorize('createUser', User::class);  
        try {
            $usuario = new User();
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->password = bcrypt($request->password);
            $usuario->save();
            
            return response()->json($usuario, 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al crear el usuario'], 500);
        }
    }

    public function destroy(string $id) {
        $this->authorize('deleteUser', User::class);

        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        } else {
            try {
                if ($usuario->id === auth()->user()->id) {
                    return response()->json(['message' => 'No puedes eliminar tu propio usuario'], 403);
                } else {
                    $usuario->delete();
                    return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
                }
            } catch (Exception $e) {
                return response()->json(['message' => 'Error al eliminar el usuario'], 500);
            }
        }
    }
     
}
