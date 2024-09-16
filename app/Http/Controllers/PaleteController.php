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
        DB::beginTransaction(); // Inicia uma transação

        try {
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'linha_documento_id' => 'required|exists:linha_documento,id',
                'localizacao' => 'nullable|array',
                'tipo_palete_id' => 'required|array',
                'artigo_id' => 'nullable|array',
                'data_entrada' => 'nullable|array',
                'armazem_id' => 'required|array',
                'descricao' => 'nullable|string', // Adiciona a validação para a descrição
            ]);

            $linhaDocumentoId = $validatedData['linha_documento_id'];
            $userId = auth()->id(); // Obtém o ID do usuário autenticado

            // Encontra a linha_documento e o documento original
            $linhaDocumento = LinhaDocumento::with('documento')->findOrFail($linhaDocumentoId);
            $documentoOriginal = $linhaDocumento->documento;

            // Cria um novo documento com tipo_documento_id fixo em 2
            $novoDocumento = Documento::create([
                'numero' => $documentoOriginal->numero, // Copia o número do documento original
                'data' => now(), // Define a data atual para o novo documento
                'tipo_documento_id' => 2, // Define o tipo_documento_id como 2
                'cliente_id' => $documentoOriginal->cliente_id,
                'user_id' => $userId, // Define o user_id
                // Adicione outros campos conforme necessário
            ]);

            // Insere os dados de paletes e cria linhas de documento para o novo documento
            foreach ($validatedData['localizacao'] as $tipoPaleteId => $localizacoes) {
                $tipoPalete = $validatedData['tipo_palete_id'][$tipoPaleteId];
                $artigoIds = $validatedData['artigo_id'][$tipoPaleteId] ?? [];
                $datasEntrada = $validatedData['data_entrada'][$tipoPaleteId] ?? [];
                $armazemIds = $validatedData['armazem_id'][$tipoPaleteId];
                $descricao = $validatedData['descricao']; // Pega a descrição do formulário ou usa null

                foreach ($localizacoes as $index => $localizacao) {
                    $artigoId = $artigoIds[$index] ?? null;
                    $dataEntrada = $datasEntrada[$index] ?? null;
                    $armazemId = $armazemIds[$index];
                    $descricaoFinal = $descricao ?? $linhaDocumento->descricao;

                    // Cria uma nova entrada na tabela `palete`
                    $palete = Palete::create([
                        'linha_documento_id' => $linhaDocumentoId,
                        'localizacao' => $localizacao,
                        'data_entrada' => $dataEntrada,
                        'tipo_palete_id' => $tipoPalete,
                        'artigo_id' => $artigoId,
                        'armazem_id' => $armazemId,
                        'user_id' => $userId,
                    ]);

                    // Adiciona uma linha no novo documento
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
                }
            }

            DB::commit(); // Confirma a transação

            // Gera o PDF após a inserção
            return $this->gerarPDF($novoDocumento->id);

        } catch (\Exception $e) {
            DB::rollback(); // Reverte a transação em caso de erro

            Log::error('Erro ao salvar paletes e criar novo documento: ' . $e->getMessage()); // Log de erro
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar as paletes e criar o novo documento: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function gerarPDF($documentoId)
    {
        try {
            // Carregar a Palete com as relações necessárias
            $palete = Palete::with([
                'linha_documento' => function ($query) {
                    $query->with('documento', 'palete'); // Inclui o Documento e Palete
                },
                'linha_documento.documento', // Inclui Documento associado a LinhaDocumento
                'tipo_palete', // Inclui TipoPalete, se necessário
                'artigo' // Inclui Artigo, se necessário
            ])->findOrFail($documentoId);

            // Prepare os dados para a view do PDF
            $data = [
                'documento' => $palete->linha_documento->documento, // Acessar o Documento
                'cliente' => $palete->linha_documento->documento->cliente, // Acessar o Cliente do Documento
                'paletes' => $palete->linha_documento->palete // Acessar todas as Paletes associadas à LinhaDocumento
            ];

            // Gera o PDF a partir da view `pdf.rececao`
            $pdf = PDF::loadView('pdf.rececao', $data);

            // Retorna o download do PDF
            return $pdf->download('nota_recepcao_' . $palete->linha_documento->documento->numero . '.pdf');
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
