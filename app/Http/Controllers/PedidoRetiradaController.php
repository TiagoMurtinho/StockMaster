<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\Artigo;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\Palete;
use App\Models\TipoDocumento;
use App\Models\TipoPalete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoRetiradaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtenha os documentos
        $documentos = Documento::with(['linha_documento.tipo_palete'])
            ->where('tipo_documento_id', 3)
            ->where('estado', 'pendente')
            ->whereHas('linha_documento', function ($query) {
                $query->orderBy('data_entrada', 'asc');
            })
            ->get();

        // Extraia os IDs dos artigos e tipos de palete
        $artigoIds = $documentos->flatMap(function ($documento) {
            return $documento->linha_documento->flatMap(function ($linha) {
                return $linha->tipo_palete->pluck('pivot.artigo_id');
            });
        })->unique()->values()->all();

        $tipoPaleteIds = $documentos->flatMap(function ($documento) {
            return $documento->linha_documento->flatMap(function ($linha) {
                return $linha->tipo_palete->pluck('pivot.tipo_palete_id');
            });
        })->unique()->values()->all();

        $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');

        $tipoPaletes = TipoPalete::whereIn('id', $tipoPaleteIds)->get()->keyBy('id');

        $paletes = Palete::whereNull('data_saida')
            ->whereIn('artigo_id', $artigoIds)
            ->whereIn('tipo_palete_id', $tipoPaleteIds)
            ->orderBy('data_entrada')
            ->get();

        $paletesPorLinha = [];

        foreach ($documentos as $documento) {
            foreach ($documento->linha_documento as $linha) {

                $paletesPorLinha[$documento->id][$linha->id] = [];

                foreach ($linha->tipo_palete as $tipoPalete) {
                    $quantidadeNecessaria = $tipoPalete->pivot->quantidade;


                    // Filtramos os paletes disponíveis para este tipo de palete e artigo
                    $paletesDisponiveis = $paletes
                        ->where('artigo_id', $tipoPalete->pivot->artigo_id)
                        ->where('tipo_palete_id', $tipoPalete->id)
                        ->sortBy('data_entrada');

                    // Se houver paletes disponíveis, acumule até atingir a quantidade necessária
                    $paletesSelecionados = collect();
                    foreach ($paletesDisponiveis as $palete) {
                        if ($paletesSelecionados->count() < $quantidadeNecessaria) {
                            $paletesSelecionados->push($palete);
                        }
                    }

                    // Armazene os paletes selecionados para este tipo de palete
                    if ($paletesSelecionados->isNotEmpty()) {
                        // Se já existem paletes para este tipo de palete, combine as coleções
                        if (!isset($paletesPorLinha[$documento->id][$linha->id][$tipoPalete->id])) {
                            $paletesPorLinha[$documento->id][$linha->id][$tipoPalete->id] = collect();
                        }

                        $paletesPorLinha[$documento->id][$linha->id][$tipoPalete->id] = $paletesPorLinha[$documento->id][$linha->id][$tipoPalete->id]->merge($paletesSelecionados);
                    }
                }
            }
        }

        // Obtenha os armazéns, tipos de documentos e clientes
        $armazens = Armazem::all();
        $tiposDocumento = TipoDocumento::all();
        $clientes = Cliente::all();
        /*$tipoPaletes = TipoPalete::all();*/

        // Retorne os dados para a view
        return view('pages.pedido.pedido-retirada.pedido-retirada', compact('documentos', 'tiposDocumento', 'clientes', 'tipoPaletes', 'armazens', 'paletes', 'paletesPorLinha', 'artigos'));
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
    public function show($id)
    {

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
