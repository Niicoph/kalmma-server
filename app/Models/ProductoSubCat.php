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



}
