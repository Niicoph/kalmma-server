<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoResource as ProductoResource;
use Illuminate\Http\Request;
use App\Http\Requests\ProductoRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Producto;
use Exception;

class ProductosController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\JSON\AnonymousResourceCollection
     */
    public function index(){
        return ProductoResource::collection(Producto::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\ProductoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductoRequest $request): JsonResponse {
        
    try {
        $validatedData = $request->validated();
        $product = Producto::create($validatedData);
        return response()->json([
            'message' => 'Producto agregado correctamente',
            'producto' => new ProductoResource($product),
        ], 201);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error inesperado al agregar el producto',
            'error' => $e->getMessage(),
        ], 500);
    }
    }

    /**
     * Display the specified resource.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id){
        try {
            $product = Producto::find($id);
            return response()->json([
                'message' => 'Producto obtenido correctamente',
                'producto' => new ProductoResource($product),
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error inesperado al obtener el producto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\ProductoRequest $request 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductoRequest $request, string $id){
        $validatedData = $request->validated();
        $product = Producto::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado',
            ], 404);
        } else {
            try {
                $product->update($validatedData);
                return response()->json([
                    'message' => 'Producto actualizado correctamente',
                    'producto' => new ProductoResource($product),
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Error inesperado al actualizar el producto',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id){
        $product = Producto::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado',
            ], 404);
        } else {
            try {
                $product->delete();
                return response()->json([
                    'message' => 'Producto eliminado correctamente',
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Error inesperado al eliminar el producto',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
}
