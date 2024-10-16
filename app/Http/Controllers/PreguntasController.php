<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pregunta;
use Exception;


class PreguntasController extends Controller {
    

    /**
     * Displays a listing of the resource.
     * @return void
     */
    public function index() {
        $preguntas = Pregunta::paginate(10);
        return response()->json($preguntas);
    }
    
    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:120',
            'respuesta' => 'required|string|max:200',
        ], [
            'pregunta.required' => 'La pregunta es obligatoria.',
            'pregunta.string' => 'La pregunta debe ser una cadena de texto válida.',
            'pregunta.max' => 'La pregunta no debe exceder los 120 caracteres.',
            'respuesta.required' => 'La respuesta es obligatoria.',
            'respuesta.string' => 'La respuesta debe ser una cadena de texto válida.',
            'respuesta.max' => 'La respuesta no debe exceder los 200 caracteres.',
        ]);
    
        try {
            $pregunta = Pregunta::create($data);
    
            return response()->json([
                'message' => 'Pregunta creada correctamente',
                'pregunta' => $pregunta,
            ], 201);
            
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error inesperado al crear la pregunta',
            ], 500);
        }
    }
    
    /**
     * Update the specified resource in storage.
     * @param string $id
     */
    public function update(string $id) {
        $data = request()->validate([
            'pregunta' => 'required|string|max:120',
            'respuesta' => 'required|string|max:200',
        ]);
        try {
            $pregunta = Pregunta::find($id);
            if (!$pregunta) {
                return response()->json([
                    'message' => 'Pregunta no encontrada',
                ], 404);
            } else {
                $pregunta->update($data);
                return response()->json([
                    'message' => 'Pregunta actualizada correctamente',
                    'pregunta' => $pregunta,
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error inesperado al actualizar la pregunta',
            ], 500);
        }
    } 

    /**
     * Remove the specified resource from storage.
     * @param string $id
     */
    public function destroy(string $id) {
        try {
            $pregunta = Pregunta::find($id);
            if (!$pregunta) {
                return response()->json([
                    'message' => 'Pregunta no encontrada',
                ], 404);
            } else {
                $pregunta->delete();
                return response()->json([
                    'message' => 'Pregunta eliminada correctamente',
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error inesperado al eliminar la pregunta',
            ], 500);
        }
    }

}
