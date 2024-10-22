<?php

use App\Http\Controllers\{ArmazemController,
    ArtigoController,
    ClienteController,
    DeviceController,
    DocumentoController,
    NotificacaoController,
    PaleteController,
    PedidoEntregaController,
    PedidoRetiradaController,
    ProfileController,
    TaxaController,
    TipoPaleteController,
    UserController};

use App\Models\{ChMessage, Cliente, Taxa, TipoPalete};

use Illuminate\Support\Facades\Route;

Route::get('/', [DeviceController::class, 'generateDeviceId']);

Route::middleware(['custom'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/home', function () {
        return view('pages.home.home');
    })->name('home');
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
    Route::get('/documento/{id}', [DocumentoController::class, 'show'])->name('documento.show');
    Route::put('/documento/{id}', [DocumentoController::class, 'update']);
    Route::get('/documento/{id}/tipos-palete', [DocumentoController::class, 'getTiposPalete']);
    Route::post('/paletes/retirar', [PaleteController::class, 'retirar'])->name('paletes.retirar');

    Route::get('tipo-paletes', function() {
        return response()->json(TipoPalete::all());
    });

    Route::get('taxas', function() {
        return response()->json(Taxa::all());
    });

    Route::get('clientes', function() {
        return response()->json(Cliente::all());
    });

    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('/documento/faturacao/{clienteId}', [DocumentoController::class, 'faturacao']);

    Route::get('/notificacoes', [NotificacaoController::class, 'index'])->name('notificacoes.index');
    Route::put('/notificacoes/marcar-lidas', [NotificacaoController::class, 'update']);

    Route::get('/clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
    Route::get('/tipoPalete/search', [TipoPaleteController::class, 'search'])->name('tipo-palete.search');
    Route::get('/armazens/search', [ArmazemController::class, 'search'])->name('armazens.search');
    Route::get('/Artigo/search', [ArtigoController::class, 'search'])->name('artigo.search');
    Route::get('/taxas/search', [TaxaController::class, 'search'])->name('taxa.search');
    Route::get('/documentos/search', [DocumentoController::class, 'search'])->name('documento.search');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/entrega/search', [PedidoEntregaController::class, 'search'])->name('entrega.search');
    Route::get('/retirada/search', [PedidoRetiradaController::class, 'search'])->name('retirada.search');
    Route::get('/unseen-messages', function () {
        $user = auth()->user();
        if ($user) {
            $unseenCounter = ChMessage::where('to_id', $user->id)
            ->where('seen', 0)
            ->count();
            return response()->json(['unseenCounter' => $unseenCounter]);
        }
        return response()->json(['unseenCounter' => 0]);
    });
});

require __DIR__.'/auth.php';
