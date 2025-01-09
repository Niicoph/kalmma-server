<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Producto;
use App\Models\Subsubcategoria;

class ProductoSubCatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $producto = $this->producto;
        $subsubcategoria = $this->subsubcategoria;
        return [
            'id' => $this->id,
            'Producto' => $producto,
            'subsubcategoria' => $subsubcategoria,
        ];
    }
}
