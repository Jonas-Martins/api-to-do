<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/404', function(){
    return response(['message' => 'Usuário não logado'], 401); 
})->name('login'); 

Route::get('/ping', function(){
    $array = ['pong' => true];
    return $array;
}); 

Route::middleware('auth:sanctum')->get('/load-session', function (Request $request) {
    return $request->user();
});

Route::prefix('user')->group(function () {
    Route::post('/create', [UserController::class, 'create']);
    Route::post('/auth', [UserController::class, 'login']);

    Route::middleware('auth:sanctum')->get('/logout', [UserController::class, 'logout']);

    Route::middleware('auth:sanctum')->put('/update', [UserController::class, 'update']);
    Route::put('/restore', [UserController::class, 'restore']);

    Route::middleware('auth:sanctum')->delete('/delete/{id}', [UserController::class, 'delete']);
    Route::middleware('auth:sanctum')->delete('/force-delete/{id}', [UserController::class, 'forceDelete']);
});

Route::prefix('category')->group(function () {
    Route::middleware('auth:sanctum')->post('/create', [CategoryController::class, 'create']);

    Route::middleware('auth:sanctum')->get('/all', [CategoryController::class, 'readAll']);

    Route::middleware('auth:sanctum')->put('/update', [CategoryController::class, 'update']);
    Route::middleware('auth:sanctum')->put('/restore', [CategoryController::class, 'restore']);

    Route::middleware('auth:sanctum')->delete('/delete/{id}', [CategoryController::class, 'delete']);
    Route::middleware('auth:sanctum')->delete('/force-delete/{id}', [CategoryController::class, 'forceDelete']);
});

Route::prefix('task')->group(function () {
    Route::middleware('auth:sanctum')->post('/create', [TaskController::class, 'create']);

    Route::middleware('auth:sanctum')->get('/all/{id}', [TaskController::class, 'readAll']);

    Route::middleware('auth:sanctum')->put('/update', [TaskController::class, 'update']);

    Route::middleware('auth:sanctum')->delete('/delete/{id}', [TaskController::class, 'delete']);

});


