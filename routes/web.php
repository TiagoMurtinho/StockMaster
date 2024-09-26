<?php

use App\Http\Controllers\ArmazemController;
use App\Http\Controllers\ArtigoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\LinhaDocumentoController;
use App\Http\Controllers\PaleteController;
use App\Http\Controllers\PedidoEntregaController;
use App\Http\Controllers\PedidoRetiradaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaxaController;
use App\Http\Controllers\TipoPaleteController;
use App\Http\Controllers\UserController;
use App\Models\Taxa;
use App\Models\TipoPalete;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('pages.home.home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('tipo-palete', TipoPaleteController::class);
Route::resource('cliente', ClienteController::class);
Route::resource('armazem', ArmazemController::class);
Route::resource('documento', DocumentoController::class)->except('show');
Route::resource('pedido-entrega', PedidoEntregaController::class);
Route::resource('pedido-retirada', PedidoRetiradaController::class)->except('show');
Route::resource('artigo', ArtigoController::class);
Route::resource('palete', PaleteController::class);
Route::resource('taxa', TaxaController::class);
Route::get('/documento/{id}/pdf', [DocumentoController::class, 'gerarPDF'])->name('documento.pdf');
Route::get('/artigos/{clienteId}', [DocumentoController::class, 'getArtigosPorCliente']);
Route::get('tipo-paletes', function() {
    return response()->json(TipoPalete::all());
});
Route::get('taxas', function() {
    return response()->json(Taxa::all());
});
Route::get('/documento/json', [DocumentoController::class, 'indexJson']);
Route::get('/documento/{id}', [DocumentoController::class, 'show'])->name('documento.show');
Route::put('/documento/{id}', [DocumentoController::class, 'update']);
Route::post('/paletes/retirar', [PaleteController::class, 'retirar'])->name('paletes.retirar');
Route::resource('user', UserController::class);


require __DIR__.'/auth.php';
