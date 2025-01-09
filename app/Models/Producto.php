<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Producto extends Model{
    use HasFactory;

    protected $fillable = [
        'SKU',
        'image_url',
        'name',
        'description',
        'espacio',
        'dimensiones',
        'categoria_id',
        'producto_url',
    ];

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    public function imagenes() {
        return $this->hasMany(ProductoImagen::class);
    }

    public function subsubcategorias() {
        return $this->belongsToMany(Subsubcategoria::class, 'producto_sub_cats', 'producto_id', 'subsubcategoria_id');
    }


}
