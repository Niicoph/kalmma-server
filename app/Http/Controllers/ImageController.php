<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Models\ProductoImagen;

class ImageController extends Controller
{
    public function showImage($type, $filename)
    {
        $basePath = 'storage/' . $type;
        $path = public_path($basePath . '/' . $filename);
        if (!File::exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=31536000', 
        ]);
    }
    public function showImageProducto($id) {
        $imagenesProducto = ProductoImagen::where('producto_id', $id)->get();
        if ($imagenesProducto->isEmpty()) {
            abort(404);
        } else {
            return response()->json($imagenesProducto);
        }
    }


}
