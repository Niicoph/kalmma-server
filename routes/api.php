<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreguntasController;
use App\Http\Controllers\UsuariosController;

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

Route::middleware('throttle:global')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});
/* Route::post('/register', [AuthController::class, 'register']); */ // eliminar en producción


Route::get('/productos', [ProductosController::class, 'index']);
Route::get('/productos/{id}', [ProductosController::class, 'show']);
Route::get('/preguntas', [PreguntasController::class, 'index']);


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
    
    // usuarios
    Route::get('/usuarios', [UsuariosController::class, 'index']);
    Route::post('/usuarios', [UsuariosController::class, 'store'])
        ->middleware('can:createUser,App\Models\User');
    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy'])
        ->middleware('can:deleteUser,App\Models\User');
});


Route::middleware('auth.jwt')->group( function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refreshToken']);
    // Relacionados a perfil
    Route::get('/perfil', [ProfileController::class, 'show']);
    Route::put('/perfil/{id}', [ProfileController::class, 'update']);
});

