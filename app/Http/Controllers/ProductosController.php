<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoResource as ProductoResource;
use App\Http\Resources\ProductoImagenResource as ProductoImagenResource;
use Illuminate\Http\Request;
use App\Http\Requests\ProductoRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Producto;
use App\Models\ProductoImagen;
use Exception;
use Illuminate\Support\Facades\Storage;

class ProductosController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\JSON\AnonymousResourceCollection
     */
    public function index(Request $request) {
        $query = Producto::query();

        if ($request->has('name') || $request->has('category') || $request->has('espacio')) {
            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            if ($request->has('category')) {
                $query->where('category', 'like', '%' . $request->category . '%');
            }
            if ($request->has('espacio')) {
                $query->where('espacio', 'like', '%' . $request->espacio . '%');
            }
        }
    
        $productos = $query->paginate(16);
        if ($productos->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron productos que coincidan con los criterios de búsqueda',
            ], 404);
        }
        return ProductoResource::collection($productos);
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
    
            $image_url_name = null;
            $imagenes = [];
    
            if ($request->hasFile('image_url')) {
                $image_url = $request->file('image_url');
                $image_url_name = hash('sha256', $product->id . $image_url->getClientOriginalName()) . '.' . $image_url->getClientOriginalExtension();
                $image_url->storeAs('public/productos', $image_url_name);
                $product->update(['image_url' => $image_url_name]);
            }

            if ($request->hasFile('images')) {
                $images = $request->file('images');
    
                foreach ($images as $image) {
                    $image_name = hash('sha256', $product->id . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/productos', $image_name);
    
                    $productoImagen = ProductoImagen::create([
                        'producto_id' => $product->id,
                        'image_detailed_url' => $image_name,
                    ]);
                    $imagenes[] = new ProductoImagenResource($productoImagen);
                }
            }
            return response()->json([
                'message' => 'Producto agregado correctamente',
                'producto' => new ProductoResource($product),
                'imagenes_del_producto' => $imagenes,
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
    public function update(ProductoRequest $request, string $id): JsonResponse {
        $validatedData = $request->validated();
    
        $product = Producto::find($id);
        
        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado',
            ], 404);
        }
    
        try {
            $product->update($validatedData);
    
            $imagenes = [];
            if ($request->hasFile('image_url')) {
                if ($product->image_url) {
                    Storage::delete('public/productos/' . $product->image_url);
                }
                $image_url = $request->file('image_url');
                $image_url_name = hash('sha256', $product->id . $image_url->getClientOriginalName()) . '.' . $image_url->getClientOriginalExtension();
                $image_url->storeAs('public/productos', $image_url_name);
                $product->update(['image_url' => $image_url_name]);
            }
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                ProductoImagen::where('producto_id', $product->id)->delete();
    
                foreach ($images as $image) {
                    $image_name = hash('sha256', $product->id . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/productos', $image_name);
                    $productoImagen = ProductoImagen::create([
                        'producto_id' => $product->id,
                        'image_detailed_url' => $image_name,
                    ]);
                    $imagenes[] = new ProductoImagenResource($productoImagen);
                }
            }
            return response()->json([
                'message' => 'Producto actualizado correctamente',
                'producto' => new ProductoResource($product),
                'imagenes_del_producto' => $imagenes,
            ], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error inesperado al actualizar el producto',
                'error' => $e->getMessage(),
            ], 500);
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
