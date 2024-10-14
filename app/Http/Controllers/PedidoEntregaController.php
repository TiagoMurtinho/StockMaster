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

        if (empty($search)) {
            return redirect()->route('pedido-entrega.index');
        }

        if (!preg_match('/^[a-zA-Z0-9\s]*$/', $search)) {
            return response()->json(['error' => 'Pesquisa inválida. Apenas letras, números e espaços são permitidos.'], 400);
        }

        $documentos = Documento::where('tipo_documento_id', 1)
            ->where('estado', 'pendente')
        ->where(function($query) use ($search) {
            $query->whereHas('cliente', function($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%');
            })
                ->orWhere('numero', 'like', '%' . $search . '%');
        })
            ->with('cliente', 'tipo_palete')
            ->get();

        if ($request->ajax()) {
            return response()->json($documentos);
        }

        return view('pages.pedido.pedido-entrega.pedido-entrega', compact('documentos'));
    }
}
