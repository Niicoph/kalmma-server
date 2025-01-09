<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'categoria_id',
    ];

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function subsubcategoria() {
        return $this->hasMany(Subsubcategoria::class);
    }

}
