<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LojaController;
use App\Http\Controllers\ProdutoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/loja', [LojaController::class, 'index'])->name("loja.index");
Route::get('/loja/{loja}', [LojaController::class, 'show'])->name("loja.show");
Route::post('/loja', [LojaController::class, 'store'])->name("loja.store");
Route::put('/loja/{loja}', [LojaController::class, 'update'])->name("loja.update");
Route::delete('/loja/{loja}', [LojaController::class, 'destroy'])->name("loja.destroy");

Route::get('/produto', [ProdutoController::class, 'index'])->name("produto.index");
Route::get('/produto/{produto}', [ProdutoController::class, 'show'])->name("produto.show");
Route::post('/produto', [ProdutoController::class, 'store'])->name("produto.store");
Route::put('/produto/{produto}', [ProdutoController::class, 'update'])->name("produto.update");
Route::delete('/produto/{produto}', [ProdutoController::class, 'destroy'])->name("produto.destroy");

