<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Subcategories;
use Exception;

class SubCategoriesController extends Controller
{
    public function index() {
        try {
            $subcategories = Subcategories::all();
            if ($subcategories->isEmpty()) {
                return response()->json(['message' => 'No hay subcategorías registradas'], 404);
            } else {
                return response()->json(['subcategories' => $subcategories], 200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las subcategorías'], 500);
        }
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:120',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'parent_id' => 'nullable|integer|exists:subcategories,id',
        ]);
        try {
            $subcategory = Subcategories::create($validatedData) ;
            if ($subcategory) {
                return response()->json($subcategory, 201);
            } else {
                return response()->json(['error' => 'Error al crear la subcategoría'], 400);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear la subcategoría'], 500);
        }
    }

    public function destroy($id) {
        $subcategory = Subcategories::find($id);
        if (!$subcategory) {
            return response()->json(['error' => "Categoria no encontrada" ], 404);
        } else {
            try {
                $subcategory->delete();
                return response()->json(['message' => 'Subcategoria eliminada con exito'], 200);
            } catch (Exception $e) {
                return response()->json(['error' => 'Error al eliminar la subcategoria'] , 400);
            }
        }
    }

    public function update(Request $request , $id) {
        //1. encontramos la sub a actualizar
        $subcategoria = Subcategories::find($id);
        if ($subcategoria) {
            // 2. Categoria encontrada ? validamos datos : ressponse
            $validatedData = $request->validate([
                "name" => 'sometimes|string|max:120',
                'categoria_id' => 'sometimes|integer|exists:categoria,id',
                'parent_id' => 'sometimes|integer|exists:Subcategories,id',
            ]);
            try {
                $subcategoria->update($validatedData);
                return response()->json(['message' => "subcategoria actualizada correctamente"],200);
            } catch (Exception $e) {
                return response()->json(['error' => "Error al actualizar la categoria"], 400);
            }
        } else {
            return response()->json(['error' => "Error. subcategoria no encontrada con ese id"] ,404);
        }
    }

}
