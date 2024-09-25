<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\LinhaDocumento;
use App\Models\DocumentoTipoPalete;
use App\Models\Palete;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();

        try {

            $validatedData = $request->validate([
                'documento_id' => 'required|exists:documento,id',
                'localizacao' => 'required|array',
                'tipo_palete_id' => 'required|array',
                'data_entrada' => 'nullable|array',
                'armazem_id' => 'required|array',
                'cliente_id' => 'required|integer|exists:cliente,id',
                'observacao' => 'nullable|string',
            ]);

            $documentoId = $validatedData['documento_id'];
            $userId = auth()->id();

            $documentoOriginal = Documento::findOrFail($documentoId);

            $observacaoFinal = !empty($validatedData['observacao']) ? $validatedData['observacao'] : $documentoOriginal->observacao;

            $novoDocumento = Documento::create([
                'numero' => $documentoOriginal->numero,
                'previsao' => $documentoOriginal->previsao,
                'data' => now(),
                'data_entrada' => now(),
                'estado' => 'terminado',
                'observacao' => $observacaoFinal,
                'tipo_documento_id' => 2,
                'cliente_id' => $documentoOriginal->cliente_id,
                'user_id' => $userId,
                'taxa_id' => $documentoOriginal->taxa_id
            ]);

            $documentoOriginal->update(['estado' => 'terminado']);

            $paletesCriadas = [];

            foreach ($validatedData['localizacao'] as $tipoPaleteId => $localizacoes) {
                $tipoPalete = $validatedData['tipo_palete_id'][$tipoPaleteId];
                $armazemIds = $validatedData['armazem_id'][$tipoPaleteId];
                $artigoId = $documentoOriginal->tipo_palete->pluck('pivot.artigo_id')->first();

                foreach ($localizacoes as $index => $localizacao) {
                    $armazemId = $armazemIds[$index] ?? null;

                    if (!$localizacao || !$armazemId || !$tipoPalete) {
                        continue;
                    }

                    $palete = Palete::create([
                        'documento_id' => $novoDocumento->id,
                        'localizacao' => $localizacao,
                        'data_entrada' => now(),
                        'tipo_palete_id' => $tipoPalete,
                        'artigo_id' => $artigoId,
                        'armazem_id' => $armazemId,
                        'cliente_id' => $documentoOriginal->cliente_id,
                        'user_id' => $userId,
                    ]);

                    DocumentoTipoPalete::create([
                        'documento_id' => $novoDocumento->id,
                        'artigo_id' => $artigoId,
                        'localizacao' => $palete->localizacao,
                        'armazem_id' => $palete->armazem->id,
                        'tipo_palete_id' => $tipoPaleteId,
                        'quantidade' => 1,
                    ]);

                    $paletesCriadas[] = $palete->id;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'documento_id' => $novoDocumento->id,
                'paletes_criadas' => $paletesCriadas,
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Erro ao salvar paletes e criar novo documento: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar as paletes e criar o novo documento: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function retirar(Request $request): JsonResponse
    {

        $request->validate([
            'paletes_selecionadas' => 'required|array',
            'documento_id' => 'required|exists:documento,id',
        ]);

        foreach ($request->paletes_selecionadas as $paleteId) {
            $palete = Palete::where('id', $paleteId)
                ->first();

            if ($palete) {
                $palete->data_saida = now();
                $palete->save();
            }
        }

        return response()->json([
            'success' => true,
            'documento_id' => $request->input('documento_id')
        ]);
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
