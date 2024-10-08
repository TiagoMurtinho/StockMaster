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
use Carbon\Carbon;
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
        $documentos = Documento::with(['tipo_palete'])
            ->where('tipo_documento_id', 3)
            ->where('estado', 'pendente')
            ->whereDate('previsao', today())
            ->orderBy('data_entrada', 'asc')
            ->paginate(10);

        $artigoIds = collect();
        $tipoPaleteIds = collect();

        foreach ($documentos as $documento) {
            $artigoIds = $artigoIds->merge($documento->tipo_palete->pluck('pivot.artigo_id'));
            $tipoPaleteIds = $tipoPaleteIds->merge($documento->tipo_palete->pluck('pivot.tipo_palete_id'));
        }

        $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');
        $tipoPaletes = TipoPalete::whereIn('id', $tipoPaleteIds)->get()->keyBy('id');

        $paletes = Palete::whereNull('data_saida')
            ->whereIn('artigo_id', $artigoIds)
            ->whereIn('tipo_palete_id', $tipoPaleteIds)
            ->orderBy('data_entrada')
            ->get();

        $paletesPorLinha = [];

        foreach ($documentos as $documento) {

            foreach ($documento->tipo_palete as $tipoPalete) {

                if (!isset($paletesPorLinha[$documento->id][$tipoPalete->id])) {
                    $paletesPorLinha[$documento->id][$tipoPalete->id] = collect();
                }

                $quantidadeNecessaria = $tipoPalete->pivot->quantidade;

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

        try {
            $validatedData = $request->validate([
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

            $documentoAntigo = Documento::where('cliente_id', $validatedData['cliente_id'])
                ->where('estado', '!=', 'terminado')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($documentoAntigo) {
                $documentoAntigo->estado = 'terminado';
                $documentoAntigo->save();
            }

            $paletesDados = json_decode($validatedData['paletes_dados'], true);

            if (!is_array($paletesDados) || count($paletesDados) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma palete foi fornecida. O documento não pode ser criado.'
                ], 422);
            }

            $novoDocumento = Documento::create([
                'numero' => $validatedData['numero'],
                'cliente_id' => $validatedData['cliente_id'],
                'morada' => $validatedData['morada'],
                'matricula' => $validatedData['matricula'],
                'data' => now(),
                'previsao_descarga' => $validatedData['previsao_descarga'],
                'estado' => 'terminado',
                'tipo_documento_id' => 4,
                'user_id' => auth()->id(),
                'observacao' => $validatedData['observacao'],
                'previsao' => $validatedData['previsao'],
                'data_saida' => now(),
                'taxa_id' => $validatedData['taxa_id'],
            ]);

            foreach ($paletesDados as $palete) {
                DocumentoTipoPalete::create([
                    'documento_id' => $novoDocumento->id,
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

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar documento: ' . $e->getMessage()
            ], 500);
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

    public function search(Request $request)
    {
        $search = $request->input('query');
        $today = Carbon::today();

        if (empty($search)) {
            return redirect()->route('pedido-retirada.index');
        }

        $documentos = Documento::where('tipo_documento_id', 3)
            ->whereDate('previsao', $today)
            ->where(function ($query) use ($search) {
                $query->where('numero', 'like', '%' . $search . '%')
                    ->orWhereHas('cliente', function ($q) use ($search) {
                        $q->where('nome', 'like', '%' . $search . '%');
                    });
            })
            ->with('cliente')
            ->get();

        if ($request->ajax()) {
            return response()->json($documentos);
        }

        return view('pages.pedido.pedido-retirada.pedido-retirada', compact('documentos'));
    }
}
