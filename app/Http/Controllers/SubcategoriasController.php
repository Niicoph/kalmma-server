<?php

namespace App\Http\Controllers;

use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Exception;

class SubcategoriasController extends Controller
{
    public function index() {
        try {
            $subcategorias = Subcategoria::all();
            if ($subcategorias->isEmpty()) {
                return response()->json(['error' => 'No se encontraron subcategorias'], 404);
            } else {
                return response()->json($subcategorias , 200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las subcategorias' ], 500);
        }
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:120',
            'categoria_id' => 'required|integer|exists:categorias,id',
        ]);

        try {
            $subcategoria = Subcategoria::create($validatedData);
            if ($subcategoria) {
                return response()->json($subcategoria , 201);
            } else {
                return response()->json(['error' => "Ocurrio un error al crear la subcategoria"]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => "ocurrio un error al crear la subcategoria" , $e], 500);
        }
    }

    public function destroy($id) {
        $subcategoria = Subcategoria::find($id);
        if (!$subcategoria) {
            return response()->json(['error' => "No se encontro una subcategoria con ese id"] , 404);
        } else {
            try {
                $subcategoria->delete();
                return response()->json(['message' => "subcategoria eliminada con exito"] , 200 );
            } catch (Exception $e) {
                return response()->json(['error' => 'Ocurrio un error al eliminar la subcategoria' , $e] , 500);
            }
        }
    }
    public function update(Request $request , $id) {
        $subcategoria = Subcategoria::find($id);
        if (!$subcategoria) {
            return response()->json(['error' => "No se encontro una subcategoria con ese id"], 404);
        } else {
            try {
                $validatedData = $request->validate([
                    'name' => "sometimes|string|max:120",
                    'categoria_id' => 'sometimes|integer|exists:categoria,id',
                ]);

                $subcategoria->update($validatedData);
                return response()->json(['message' => "subcategoria actualizada correctamente"],200);
            } catch (Exception $e) {
                return response()->json(['error' => "Error al actualizar la categoria", $e], 500);
            }
        }
    }

}
