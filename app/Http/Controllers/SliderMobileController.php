<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SliderMobileImagen;
use Exception;

class SliderMobileController extends Controller
{
    public function index() {
        try {
            $sliderImages = SliderMobileImagen::all();
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
            'url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        try {
            $imagen_desktop = $validatedData['url'];
            $imagen_desktop_name = hash('sha256', $imagen_desktop->getClientOriginalName() . microtime()) . '.' . $imagen_desktop->getClientOriginalExtension();
            $imagen_desktop->storeAs('public/slider', $imagen_desktop_name);
            $sliderImage = SliderMobileImagen::create([
                'url' => $imagen_desktop_name,
            ]);
    
            return response()->json($sliderImage, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al agregar la imagen'], 500);
        }
    }
}
