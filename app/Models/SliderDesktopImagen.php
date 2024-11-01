<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderDesktopImagen extends Model
{
    use HasFactory;
    protected $table = 'slider_desktop_imagenes'; 
    protected $fillable = ['url'];
}
