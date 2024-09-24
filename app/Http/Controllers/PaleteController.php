<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\LinhaDocumento;
use App\Models\LinhaDocumentoTipoPalete;
use App\Models\Palete;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $validatedData = $request->validate([
                'linha_documento_id' => 'required|exists:linha_documento,id',
                'localizacao' => 'nullable|array',
                'tipo_palete_id' => 'required|array',
                'data_entrada' => 'nullable|array',
                'armazem_id' => 'required|array',
                'cliente_id' => 'required|integer|exists:cliente,id',
                'observacao' => 'nullable|string',
            ]);

            $linhaDocumentoId = $validatedData['linha_documento_id'];
            $userId = auth()->id();

            $linhaDocumento = LinhaDocumento::with('documento')->findOrFail($linhaDocumentoId);
            $documentoOriginal = $linhaDocumento->documento;

            $observacao = $validatedData['observacao'];
            $observacaoFinal = $observacao ?? $linhaDocumento->observacao;

            $novoDocumento = Documento::create([
                'numero' => $documentoOriginal->numero,
                'data' => now(),
                'estado' => 'terminado',
                'tipo_documento_id' => 2,
                'cliente_id' => $documentoOriginal->cliente_id,
                'user_id' => $userId,
            ]);

            $novaLinhaDocumento = LinhaDocumento::create([
                'documento_id' => $novoDocumento->id,
                'previsao' => $linhaDocumento->previsao,
                'data_entrada' =>  now(),
                'observacao' => $observacaoFinal,
                'taxa_id' => $linhaDocumento->taxa->id,
                'user_id' => $userId,
            ]);

            $documentoOriginal->update(['estado' => 'terminado']);

            $paletesCriadas = [];

            foreach ($validatedData['localizacao'] as $tipoPaleteId => $localizacoes) {
                $tipoPalete = $validatedData['tipo_palete_id'][$tipoPaleteId];
                $armazemIds = $validatedData['armazem_id'][$tipoPaleteId];
                $artigoId = $linhaDocumento->tipo_palete->pluck('pivot.artigo_id')->first();

                foreach ($localizacoes as $index => $localizacao) {
                    $armazemId = $armazemIds[$index] ?? null;

                    if (!$localizacao || !$armazemId || !$tipoPalete) {
                        continue;
                    }

                    $palete = Palete::create([
                        'linha_documento_id' => $novaLinhaDocumento->id,
                        'localizacao' => $localizacao,
                        'data_entrada' => '2024-09-05',
                        'tipo_palete_id' => $tipoPalete,
                        'artigo_id' => $artigoId,
                        'armazem_id' => $armazemId,
                        'cliente_id' => $documentoOriginal->cliente_id,
                        'user_id' => $userId,
                    ]);

                    LinhaDocumentoTipoPalete::create([
                        'linha_documento_id' => $novaLinhaDocumento->id,
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

    public function retirar(Request $request)
    {
        // Validar os dados de entrada
        $request->validate([
            'paletes_selecionadas' => 'required|array',
            'linha_documento_id' => 'required|exists:linha_documento,id',
        ]);

        // Verificar o que foi enviado no request
        \Log::info('Dados recebidos:', $request->all());

        // Atualizar cada palete selecionada
        foreach ($request->paletes_selecionadas as $paleteId) {
            $palete = Palete::where('id', $paleteId)
                ->first();

            // Adicione um log para verificar se a palete foi encontrada
            if ($palete) {
                $palete->data_saida = now();
                $palete->save();
                \Log::info('Palete atualizada:', ['id' => $palete->id]);
            } else {
                \Log::warning('Palete não encontrada:', ['paleteId' => $paleteId, 'linha_documento_id' => $request->linha_documento_id]);
            }
        }

        return redirect()->back()->with('success', 'Paletes atualizadas com sucesso.');
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
