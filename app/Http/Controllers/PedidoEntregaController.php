<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\Artigo;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\TipoDocumento;
use App\Models\TipoPalete;
use Illuminate\Http\Request;

class PedidoEntregaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $documentos = Documento::with('linha_documento.tipo_palete')
            ->where('tipo_documento_id', 1)
            ->where('estado', 'pendente')
            ->whereHas('linha_documento', function ($query) {
                $query->orderBy('linha_documento.previsao', 'asc');
            })
            ->get();

        $armazens = Armazem::all();
        $tiposDocumento = TipoDocumento::all();
        $clientes = Cliente::all();
        $tipoPaletes = TipoPalete::all();

        $artigosPorCliente = [];

        foreach ($documentos as $documento) {
            $clienteId = $documento->cliente_id;

            if (!isset($artigosPorCliente[$clienteId])) {
                $artigosPorCliente[$clienteId] = Artigo::where('cliente_id', $clienteId)->get();
            }
        }

        return view('pages.pedido.pedido-entrega.pedido-entrega', compact('documentos', 'artigosPorCliente', 'tiposDocumento', 'clientes', 'tipoPaletes', 'armazens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
