<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CategoriaController extends Controller
{
    /**
     * Display a listing of categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $categorias = Categoria::all();

            return response()->json($categorias, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las categorías'], 500);
        }
    }

    /**
     * Store a newly created category.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'required|string|max:255',
        ]);
        try {
            $categoria = Categoria::create($validatedData);
            return response()->json($categoria, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la categoría' , $e], 500);
        }
    }

    public function destroy($id) {
        $category = Categoria::find($id);
        if ($category) {
            try {
                $category->delete();
                return response()->json(['message' => 'Categoría eliminada'], 200);
            } catch (\Exception $e) {
                if ($e->getCode() == 23000) {
                    return response()->json(['error' => 'No se puede eliminar la categoría porque tiene productos asociados'], 400);
                } else {
                    return response()->json(['error' => 'Error al eliminar la categoría'], 500);
                }
            }
        } else {
            return response()->json(['error' => 'Categoría no encontrada'], 404);
        }
    }

    public function update(Request $request , $id) : JsonResponse {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:120',
            'description' => 'sometimes|string|max:255',
        ]);
        try {
            $categoria = Categoria::find($id);
            if ($categoria) {
                $categoria->update($validatedData);
                return response()->json($categoria, 200);
            } else {
                return response()->json(['error' => 'Categoría no encontrada'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la categoría'], 500);
        }
    }

}
