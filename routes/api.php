<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PythonController;
use App\Http\Controllers\PythonController2;
use App\Http\Controllers\PythonController3;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::view('/web', 'web')->name('web');

Route::post('/images', [ImageController::class, 'storeAndProcessImage'])->name('images.upload');

Route::post('/execute-python-and-search', [PythonController::class, 'executePythonAndSearch']);

Route::post('/execute-python-and-search1', [PythonController1::class, 'executePythonAndSearch']);

Route::post('/execute-python-and-search3', [PythonController3::class, 'executePythonAndSearch']);