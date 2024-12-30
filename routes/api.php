<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreguntasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SliderDesktopController;
use App\Http\Controllers\SliderMobileController;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes

Route::middleware('throttle:20,10')->group(function () {
    Route::post('/auth/validate', [AuthController::class, 'isAuth']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']); // eliminar en producción
});

Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/productos', [ProductosController::class, 'index']);
// ->middleware('cache.products');

Route::middleware('throttle:images')->get('/images/{type}/{filename}', [ImageController::class, 'showImage']);


Route::get('/productos/images/{id}', [ImageController::class, 'showImageProducto']);

Route::get('/productos/{id}', [ProductosController::class, 'show']);
Route::get('/preguntas', [PreguntasController::class, 'index']);
Route::get('/slider/desktop', [SliderDesktopController::class, 'index']);
Route::get('/slider/mobile', [SliderMobileController::class, 'index']);

// Protected endpoints
Route::middleware('auth.jwt')->group(function () {
    // productos
    Route::post('/productos', [ProductosController::class, 'store']);
    Route::put('/productos/{id}', [ProductosController::class, 'update']);
    Route::delete('/productos/{id}', [ProductosController::class, 'destroy']);

    // preguntas
    Route::post('/preguntas', [PreguntasController::class, 'store']);
    Route::put('/preguntas/{id}', [PreguntasController::class, 'update']);
    Route::delete('/preguntas/{id}', [PreguntasController::class, 'destroy']);

    // categorias
    Route::post('/categorias', [CategoriaController::class, 'store']);

    // Slider
    Route::post('/slider/desktop', [SliderDesktopController::class, 'store']);
    Route::post('/slider/mobile', [SliderMobileController::class, 'store']);
    Route::delete('/slider/desktop/{id}', [SliderDesktopController::class, 'destroy']);
    Route::delete('/slider/mobile/{id}', [SliderMobileController::class, 'destroy']);

    // usuarios
    Route::get('/usuarios', [UsuariosController::class, 'index']);
    Route::post('/usuarios', [UsuariosController::class, 'store'])
        ->middleware('can:createUser,App\Models\User');
    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy'])
        ->middleware('can:deleteUser,App\Models\User');

    // perfil y autenticación
    Route::get('/perfil', [ProfileController::class, 'show']);
    Route::put('/perfil/{id}', [ProfileController::class, 'update']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);

});
