<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\Artigo;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\LinhaDocumento;
use App\Models\DocumentoTipoPalete;
use App\Models\Palete;
use App\Models\TipoDocumento;
use App\Models\TipoPalete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoRetiradaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtém os documentos com a relação tipo_palete
        $documentos = Documento::with(['tipo_palete'])
            ->where('tipo_documento_id', 3)
            ->where('estado', 'pendente')
            ->whereDate('previsao', today())
            ->orderBy('data_entrada', 'asc')
            ->get();

        // Inicializa coleções para artigoIds e tipoPaleteIds
        $artigoIds = collect();
        $tipoPaleteIds = collect();

        // Itera sobre documentos para coletar IDs de artigos e tipos de palete
        foreach ($documentos as $documento) {
            $artigoIds = $artigoIds->merge($documento->tipo_palete->pluck('pivot.artigo_id'));
            $tipoPaleteIds = $tipoPaleteIds->merge($documento->tipo_palete->pluck('pivot.tipo_palete_id'));
        }

        // Obtém artigos e tipos de palete
        $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');
        $tipoPaletes = TipoPalete::whereIn('id', $tipoPaleteIds)->get()->keyBy('id');

        // Obtém paletes disponíveis
        $paletes = Palete::whereNull('data_saida')
            ->whereIn('artigo_id', $artigoIds)
            ->whereIn('tipo_palete_id', $tipoPaleteIds)
            ->orderBy('data_entrada')
            ->get();

        $paletesPorLinha = [];

        foreach ($documentos as $documento) {
            // Itera sobre os tipos de palete diretamente
            foreach ($documento->tipo_palete as $tipoPalete) {
                // Define a estrutura do array para paletes por linha
                if (!isset($paletesPorLinha[$documento->id][$tipoPalete->id])) {
                    $paletesPorLinha[$documento->id][$tipoPalete->id] = collect();
                }

                $quantidadeNecessaria = $tipoPalete->pivot->quantidade;

                // Filtra os paletes disponíveis
                $paletesDisponiveis = $paletes
                    ->where('artigo_id', $tipoPalete->pivot->artigo_id)
                    ->where('tipo_palete_id', $tipoPalete->id)
                    ->sortBy('data_entrada');

                $paletesSelecionados = collect();
                foreach ($paletesDisponiveis as $palete) {
                    if ($paletesSelecionados->count() < $quantidadeNecessaria) {
                        $paletesSelecionados->push($palete);
                    }
                }

                // Adiciona os paletes selecionados à estrutura
                if ($paletesSelecionados->isNotEmpty()) {
                    $paletesPorLinha[$documento->id][$tipoPalete->id] = $paletesSelecionados;
                }
            }
        }

        $armazens = Armazem::all();
        $tiposDocumento = TipoDocumento::all();
        $clientes = Cliente::all();

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
        Log::info('Requisição recebida:', $request->all());

        try {

            $request->validate([
                'numero' => 'required|string|max:255',
                'cliente_id' => 'required|integer',
                'observacao' => 'nullable|string',
                'previsao' => 'nullable|date',
                'taxa_id' => 'nullable|integer',
                'matricula' => 'required|string|max:255',
                'morada' => 'nullable|string|max:255',
                'previsao_descarga' => 'nullable|date',
                'paletes_dados' => 'required|json',
            ]);

            $novoDocumento = Documento::create([
                'numero' => $request->input('numero'),
                'cliente_id' => $request->input('cliente_id'),
                'morada' => $request->input('morada'),
                'matricula' => $request->input('matricula'),
                'data' => now(),
                'previsao_descarga' => $request->input('previsao_descarga'),
                'estado' => 'terminado',
                'tipo_documento_id' => 4,
                'user_id' => auth()->id(),
                'observacao' => $request->input('observacao'),
                'previsao' => $request->input('previsao'),
                'data_saida' => now(),
                'taxa_id' => $request->input('taxa_id'),
            ]);

            $paletesDados = json_decode($request->input('paletes_dados'), true);

            if (!is_array($paletesDados)) {
                return redirect()->route('documento.index')->with('error', 'Dados das paletes inválidos.');
            }

            foreach ($paletesDados as $palete) {
                DocumentoTipoPalete::create([
                    'documento_id' => $novoDocumento->id,  // Associar ao novo documento
                    'tipo_palete_id' => $palete['tipo_palete_id'],
                    'artigo_id' => $palete['artigo_id'],
                    'armazem_id' => $palete['armazem_id'],
                    'localizacao' => $palete['localizacao'],
                    'quantidade' => 1
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Documento criado com sucesso!',
                'documento' => $novoDocumento
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar documento: ' . $e->getMessage());
            return redirect()->route('documento.index')->with('error', 'Erro ao criar documento: ' . $e->getMessage());
        }
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
