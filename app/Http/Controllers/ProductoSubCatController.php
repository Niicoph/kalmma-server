<?php
namespace App\Http\Controllers;

use App\Http\Resources\ProductoSubCatResource;
use App\Models\ProductoSubCat;
use Illuminate\Http\Request;

class ProductoSubCatController extends Controller
{
    public function index() {
        $producto_sub_cat = ProductoSubCat::all();

        if ($producto_sub_cat->isEmpty()) {
            return response()->json(['error' => "No se encontraron productos con subsubcategorias"], 404);
        } else {
            return response()->json(ProductoSubCatResource::collection($producto_sub_cat), 200);
        }
    }
}
