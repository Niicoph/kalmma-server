<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsubcategoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subcategoria_id',
    ];

    public function subcategoria() {
        return $this->belongsTo(Subcategoria::class);
    }

    public function productos() {
        return $this->belongsToMany(Producto::class, 'producto_sub_cats', 'subsubcategoria_id', 'producto_id');
    }

}
