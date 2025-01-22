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
use Illuminate\Support\Facades\DB;

class ProductosController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $espacio = $request->query('espacio');
        $category = $request->query('categoria_id');
        $name = $request->query('name');
        $subsubcategoria_id = $request->query('subsubcategoria_id');

        // Construcción de la consulta base
        $query = DB::table('productos')
            ->leftJoin('producto_sub_cats', 'productos.id', '=', 'producto_sub_cats.producto_id')
            ->select(
                'productos.id',
                'productos.SKU',
                'productos.name',
                'productos.description',
                'productos.espacio',
                'productos.dimensiones',
                'productos.categoria_id',
                'productos.image_url',
                'productos.producto_url',
                'producto_sub_cats.subsubcategoria_id'
            );

        if ($espacio) {
            $query->where('productos.espacio', $espacio);
        }
        if ($category) {
            $query->where('productos.categoria_id', $category);
        }
        if ($name) {
            $query->where('productos.name', 'like', '%' . $name . '%');
        }
        if ($subsubcategoria_id) {
            $query->where('producto_sub_cats.subsubcategoria_id', $subsubcategoria_id);
        }

        $productos = $query->paginate(34);

        $productosAgrupados = collect($productos->items())->groupBy('id')->map(function ($productosGrupo) {
            $producto = $productosGrupo->first();
            $producto->subsubcategorias = $productosGrupo->pluck('subsubcategoria_id')->unique()->values();

            return $producto;
        });

        if ($productosAgrupados->isEmpty()) {
            return response()->json([
                'message' => 'No se han encontrado productos que coincidan con tu selección.'
            ], 404);
        }

        return ProductoResource::collection($productosAgrupados->values())
            ->additional([
                'meta' => [
                    'current_page' => $productos->currentPage(),
                    'last_page' => $productos->lastPage(),
                    'per_page' => $productos->perPage(),
                    'total' => $productos->total(),
                ],
                'links' => [
                    'first' => $productos->url(1),
                    'last' => $productos->url($productos->lastPage()),
                    'prev' => $productos->previousPageUrl(),
                    'next' => $productos->nextPageUrl(),
                ],
            ]);
    }




    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\ProductoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductoRequest $request): JsonResponse
    {
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
            if (isset($validatedData['subsubcategorias'])) {
                $product->subsubcategorias()->attach($validatedData['subsubcategorias']);
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
    public function show(string $id)
    {
        $query = DB::table('productos')
            ->leftJoin('producto_sub_cats', 'productos.id', '=', 'producto_sub_cats.producto_id')
            ->select(
                'productos.id',
                'productos.SKU',
                'productos.name',
                'productos.description',
                'productos.espacio',
                'productos.dimensiones',
                'productos.categoria_id',
                'productos.image_url',
                'productos.producto_url',
                DB::raw('GROUP_CONCAT(producto_sub_cats.subsubcategoria_id) as subsubcategorias')
            )
            ->where('productos.id', $id)
            ->groupBy(
                'productos.id',
                'productos.SKU',
                'productos.name',
                'productos.description',
                'productos.espacio',
                'productos.dimensiones',
                'productos.categoria_id',
                'productos.image_url',
                'productos.producto_url'
            );

        try {
            $producto = $query->first();
            if (!$producto) {
                return response()->json([
                    'message' => 'Producto no encontrado',
                ], 404);
            }

            $producto->subsubcategorias = $producto->subsubcategorias ? array_map('intval', explode(',', $producto->subsubcategorias)) : [];

            return response()->json([
                'message' => 'Producto obtenido correctamente',
                'producto' => new ProductoResource($producto)
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
    public function update(ProductoRequest $request, string $id): JsonResponse
    {
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
            if (isset($validatedData['subsubcategorias'])) {
                $product->subsubcategorias()->attach($validatedData['subsubcategorias']);
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
    public function destroy(string $id)
    {
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
    public function buscarPorSku(Request $request, $sku)
    {
        try {
            $producto = Producto::where('sku', $sku)->first();
            if ($producto) {
                return response()->json(new ProductoResource($producto));
            }
            return response()->json(['message' => 'Producto no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error del servidor', 'error' => $e->getMessage()], 500);
        }
    }
}
