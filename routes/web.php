<?php

use App\Http\Controllers\{ArmazemController,
    ArtigoController,
    ClienteController,
    DocumentoController,
    NotificacaoController,
    PaleteController,
    PedidoEntregaController,
    PedidoRetiradaController,
    ProfileController,
    TaxaController,
    TipoPaleteController,
    UserController};

use App\Models\{
    Taxa,
    TipoPalete,
};

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('pages.home.home');
})->middleware(['custom'])->name('home');

Route::middleware('custom')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tipo-palete', TipoPaleteController::class);
    Route::resource('cliente', ClienteController::class);
    Route::resource('armazem', ArmazemController::class);
    Route::resource('documento', DocumentoController::class)->except('show');
    Route::resource('pedido-entrega', PedidoEntregaController::class);
    Route::resource('pedido-retirada', PedidoRetiradaController::class)->except('show');
    Route::resource('artigo', ArtigoController::class);
    Route::resource('palete', PaleteController::class);
    Route::resource('taxa', TaxaController::class);
    Route::resource('user', UserController::class);

    Route::get('/documento/{id}/pdf', [DocumentoController::class, 'gerarPDF'])->name('documento.pdf');
    Route::get('/artigos/{clienteId}', [DocumentoController::class, 'getArtigosPorCliente']);
    Route::get('/documento/json', [DocumentoController::class, 'indexJson']);
    Route::get('/documento/{id}', [DocumentoController::class, 'show'])->name('documento.show');
    Route::put('/documento/{id}', [DocumentoController::class, 'update']);
    Route::post('/paletes/retirar', [PaleteController::class, 'retirar'])->name('paletes.retirar');

    Route::get('tipo-paletes', function() {
        return response()->json(TipoPalete::all());
    });

    Route::get('taxas', function() {
        return response()->json(Taxa::all());
    });

    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('/documento/faturacao/{clienteId}', [DocumentoController::class, 'faturacao']);

    Route::get('/notificacoes', [NotificacaoController::class, 'index'])->name('notificacoes.index');
    Route::put('/notificacoes/marcar-lidas', [NotificacaoController::class, 'update']);
});

require __DIR__.'/auth.php';
