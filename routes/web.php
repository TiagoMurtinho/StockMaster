<?php

use App\Http\Controllers\ArmazemController;
use App\Http\Controllers\ArtigoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\LinhaDocumentoController;
use App\Http\Controllers\PedidoEntregaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\TipoPaleteController;
use App\Models\TipoPalete;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('tipo-palete', TipoPaleteController::class);
Route::resource('cliente', ClienteController::class);
Route::resource('armazem', ArmazemController::class);
Route::resource('tipo-documento', TipoDocumentoController::class);
Route::resource('documento', DocumentoController::class);
Route::resource('linha-documento', LinhaDocumentoController::class);
Route::resource('pedido-entrega', PedidoEntregaController::class);
Route::resource('artigo', ArtigoController::class);
Route::get('/documento/{id}/pdf', [DocumentoController::class, 'gerarPDF'])->name('documento.pdf');
Route::get('tipo-paletes', function() {
    return response()->json(TipoPalete::all());
});

require __DIR__.'/auth.php';
