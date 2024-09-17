<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\LinhaDocumento;
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
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'linha_documento_id' => 'required|exists:linha_documento,id',
                'localizacao' => 'nullable|array',
                'tipo_palete_id' => 'required|array',
                'artigo_id' => 'nullable|array',
                'data_entrada' => 'nullable|array',
                'armazem_id' => 'required|array',
                'descricao' => 'nullable|string',
            ]);

            $linhaDocumentoId = $validatedData['linha_documento_id'];
            $userId = auth()->id();

            $linhaDocumento = LinhaDocumento::with('documento')->findOrFail($linhaDocumentoId);
            $documentoOriginal = $linhaDocumento->documento;

            $novoDocumento = Documento::create([
                'numero' => $documentoOriginal->numero,
                'data' => now(),
                'estado' => 'terminado',
                'tipo_documento_id' => 2,
                'cliente_id' => $documentoOriginal->cliente_id,
                'user_id' => $userId,
            ]);

            $documentoOriginal->update(['estado' => 'terminado']);

            $paletesCriadas = [];

            foreach ($validatedData['localizacao'] as $tipoPaleteId => $localizacoes) {
                $tipoPalete = $validatedData['tipo_palete_id'][$tipoPaleteId];
                $artigoIds = $validatedData['artigo_id'][$tipoPaleteId] ?? [];
                $datasEntrada = $validatedData['data_entrada'][$tipoPaleteId] ?? [];
                $armazemIds = $validatedData['armazem_id'][$tipoPaleteId];
                $descricao = $validatedData['descricao'];

                foreach ($localizacoes as $index => $localizacao) {
                    $artigoId = $artigoIds[$index] ?? null;
                    $dataEntrada = $datasEntrada[$index] ?? null;
                    $armazemId = $armazemIds[$index] ?? null;

                    if (!$localizacao || !$armazemId || !$tipoPalete) {
                        continue;
                    }

                    $descricaoFinal = $descricao ?? $linhaDocumento->descricao;

                    $palete = Palete::create([
                        'linha_documento_id' => $linhaDocumentoId,
                        'localizacao' => $localizacao,
                        'data_entrada' => $dataEntrada,
                        'tipo_palete_id' => $tipoPalete,
                        'artigo_id' => $artigoId,
                        'armazem_id' => $armazemId,
                        'user_id' => $userId,
                    ]);

                    LinhaDocumento::create([
                        'documento_id' => $novoDocumento->id,
                        'tipo_palete_id' => $tipoPalete,
                        'localizacao' => $localizacao,
                        'data_entrada' => $dataEntrada,
                        'artigo_id' => $artigoId,
                        'armazem_id' => $armazemId,
                        'descricao' => $descricaoFinal,
                        'user_id' => $userId,
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

    public function gerarPDF($documentoId, Request $request)
    {
        try {
            $paletesIds = $request->input('paletes_criadas', []);

            // Filtra apenas as paletes criadas
            $paletes = Palete::with([
                'linha_documento' => function ($query) {
                    $query->with('documento');
                },
                'linha_documento.documento',
                'tipo_palete',
                'artigo',
                'armazem',
            ])->whereIn('id', $paletesIds)->get();

            if ($paletes->isEmpty()) {
                throw new \Exception('Nenhuma palete encontrada.');
            }

            $documento = $paletes->first()->linha_documento->documento;
            $cliente = $documento->cliente;

            if (!$cliente) {
                throw new \Exception('Cliente não encontrado.');
            }

            $data = [
                'documento' => $documento,
                'cliente' => $cliente,
                'palete' => $paletes,
            ];

            $pdf = PDF::loadView('pdf.rececao', $data);

            // Retorna o PDF diretamente como download
            return $pdf->download('nota_recepcao_' . $documento->numero . '.pdf');

        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar o PDF: ' . $e->getMessage(),
            ], 500);
        }
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
