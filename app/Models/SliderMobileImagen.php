<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderMobileImagen extends Model
{
    use HasFactory;
    protected $table = 'slider_mobile_imagenes'; 
    protected $fillable = ['url'];
}
