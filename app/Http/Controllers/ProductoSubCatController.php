<?php
namespace App\Http\Controllers;

use App\Http\Resources\ProductoSubCatResource;
use App\Models\ProductoSubCat;
use Illuminate\Http\Request;

class ProductoSubCatController extends Controller
{
    // public function index(Request $request) {
    //     $filters = $request->only(['espacio' , 'name' ,'categoria_id' , 'subsubcategoria_id']);
    //     $query = ProductoSubCat::query();
    //     if (isset($filters['espacio'])) {
    //         $query->where('espacio', $filters['espacio']);
    //     }
    //     if (isset($filters['name'])) {
    //         $query->where('name', 'like', '%' . $filters['name'] . '%');
    //     }
    //     if (isset($filters['categoria_id'])) {
    //         $query->where('categoria_id', $filters['categoria_id']);
    //     }
    //     if (isset($filters['subsubcategoria_id'])) {
    //         $query->where('subsubcategoria_id', $filters['subsubcategoria_id']);
    //     }
    //     $producto_sub_cat = $query->paginate(30);

    //     if ($producto_sub_cat->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No se han encontrado productos que coincidan con tu selección.'
    //         ], 404);
    //     }
    //     return ProductoSubCatResource::collection($producto_sub_cat);
    // }
}
