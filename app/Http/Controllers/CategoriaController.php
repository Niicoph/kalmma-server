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

            if ($categorias->isEmpty()) {
                return response()->json(['message' => 'No se encontraron categorías'], 404);
            }

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
            return response()->json(['error' => 'Error al crear la categoría'], 500);
        }
    }
}
