<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'categoria_id',
        'parent_id',
    ];

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }
    public function parent() {
        return $this->belongsTo(Subcategories::class, 'parent_id');
    }
    public function children() {
        return $this->hasMany(Subcategories::class, 'parent_id');
    }

}
