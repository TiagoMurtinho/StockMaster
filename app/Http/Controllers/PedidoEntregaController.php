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

        $documentos = Documento::with('tipo_palete')
            ->where('tipo_documento_id', 1)
            ->where('estado', 'pendente')
            ->orderBy('documento.previsao', 'asc')
            ->paginate(10);

        $armazens = Armazem::all();
        $tiposDocumento = TipoDocumento::all();
        $clientes = Cliente::all();
        $tipoPaletes = TipoPalete::all();

        return view('pages.pedido.pedido-entrega.pedido-entrega', compact('documentos','tiposDocumento', 'clientes', 'tipoPaletes', 'armazens'));
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

    public function search(Request $request)
    {
        $search = $request->input('query');

        $documentos = Documento::where('tipo_documento_id', 1)
        ->where(function($query) use ($search) {
            $query->whereHas('cliente', function($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%');
            })
                ->orWhere('numero', 'like', '%' . $search . '%');
        })
            ->with('cliente', 'tipo_palete')
            ->get();

        return response()->json($documentos);
    }
}
