<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoSubCat extends Model
{
    use HasFactory;

    protected $table = 'producto_sub_cats';
    protected $fillable = [
        'producto_id',
        'subsubcategoria_id',
    ];

    public function producto() {
        return $this->belongsTo(Producto::class);
    }

    public function subsubcategoria() {
        return $this->belongsTo(Subsubcategoria::class);
    }

}
