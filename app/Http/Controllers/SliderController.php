<?php

namespace App\Http\Controllers;

use App\Models\SliderImagen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class SliderController extends Controller
{
    public function index() {
        try {
            $sliderImages = SliderImagen::all();
            if ($sliderImages->isEmpty()) {
                return response()->json(['message' => 'No se encontraron imágenes cargadas'], 404);
            }
            return response()->json($sliderImages, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las imágenes del slider'], 500);
        }
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'imagen_desktop' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'imagen_mobile' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        try {
            $imagen_desktop = $validatedData['imagen_desktop'];
            $imagen_desktop_name = hash('sha256', $imagen_desktop->getClientOriginalName() . microtime()) . '.' . $imagen_desktop->getClientOriginalExtension();
            $imagen_desktop->storeAs('public/slider', $imagen_desktop_name);
    
            $imagen_mobile_name = null;
            if (isset($validatedData['imagen_mobile'])) {
                $imagen_mobile = $validatedData['imagen_mobile'];
                $imagen_mobile_name = hash('sha256', $imagen_mobile->getClientOriginalName() . microtime()) . '.' . $imagen_mobile->getClientOriginalExtension();
                $imagen_mobile->storeAs('public/slider', $imagen_mobile_name);
            }
            $sliderImage = SliderImagen::create([
                'imagen_desktop' => $imagen_desktop_name,
                'imagen_mobile' => $imagen_mobile_name,
            ]);
    
            return response()->json($sliderImage, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al agregar la imagen'], 500);
        }
    }
}
