<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku'  => $this->SKU,
            'nombre' => $this->name,
            'descripcion' => $this->description,
            'espacio' => $this->espacio,
            'dimensiones' => $this->dimensiones,
            'categoria_id' => $this->categoria_id,
            'image_url' => $this->image_url,
            'producto_url' => $this->producto_url,
        ];
    }
}
