<?php

namespace App\Http\Controllers;

use App\Models\Subsubcategoria;
use Exception;
use Illuminate\Http\Request;

class SubsubcategoriasController extends Controller
{
    public function index(){
        try {
            $subsubcategorias = Subsubcategoria::all();
            if ($subsubcategorias->isEmpty()) {
                return response()->json(['error' => 'No se encontraron subsubcategorias'], 404);
            } else {
                return response()->json($subsubcategorias, 200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las subsubcategorias' , $e ], 500);
        }
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:120',
            'subcategoria_id' => 'required|integer|exists:subcategorias,id',
        ]);

        try {
            $subsubcategoria = Subsubcategoria::create($validatedData);
            if ($subsubcategoria) {
                return response()->json($subsubcategoria , 201);
            } else {
                return response()->json(['error' => "Ocurro un error al crear la subsubcategoria"], 400 );
            }
        } catch (Exception $e) {
            return response()->json(['error' => "ocurrio un error al crear la subsubcategoria" , $e], 500);
        }
    }

    public function destroy($id) {
        $subsubcategoria = Subsubcategoria::find($id);
        if (!$subsubcategoria) {
            return response()->json(['error' => "No se encontro una subsubcategoria con ese id"] , 404);
        } else {
            try {
                $subsubcategoria->delete();
                return response()->json(['message' => "subsubcategoria eliminada con exito"] , 200 );
            }   catch (Exception $e) {
                return response()->json(['error' => 'Ocurrio un error al eliminar la subsubcategoria' , $e] , 500);
            }
        }
    }

    public function update(Request $request , $id) {
        $subsubcategoria = Subsubcategoria::find($id);
        if (!$subsubcategoria) {
            return response()->json(['error' => "No se encontro una subcategoria con ese id"], 404);
        } else {
            try {
                $validatedData = $request->validate([
                    'name' => 'sometimes|string|max:120',
                    'subcategoria_id' => 'sometimes|integer|exists:subcategorias,id',
                ]);

                $subsubcategoria->update($validatedData);
                return response()->json(['message' => "subsubcategoria actualizada correctamente"],200);
            } catch (Exception $e) {
                return response()->json(['error' => "Error al actualizar la categoria", $e], 500);
            }
        }
    }

}
