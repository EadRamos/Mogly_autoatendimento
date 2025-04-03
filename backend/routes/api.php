<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;


route::get('/', function (Request $request) {
    return response()->json(['message' => 'ola'],200);
});
route::post('/login', [AuthController::class, 'login']);
route::post('/registrar', [AuthController::class, 'registrar']);
route::post('/logout', [AuthController::class, 'logout']);

route::get('/login-mesa/${restaurante}/${mesa}', [AuthController::class, 'autenticarMesa']);

// rota para usuarios logados
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

