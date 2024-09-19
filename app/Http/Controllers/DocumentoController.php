<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\Artigo;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\LinhaDocumento;
use App\Models\TipoDocumento;
use App\Models\TipoPalete;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {

        $documentos = Documento::all();
        $tiposDocumento = TipoDocumento::where('id', 1)->get();
        $clientes = Cliente::all();
        $tipoPaletes = TipoPalete::all();
        return view('pages.admin.documento.documento', compact('documentos', 'tiposDocumento', 'clientes', 'tipoPaletes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $tiposDocumentos = TipoDocumento::all();
        $clientes = Cliente::all();

        return view('pages.documento.documento', compact('tiposDocumentos', 'clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'numero' => 'required|numeric',
                'data' => 'required|date',
                'matricula' => 'nullable|string|max:45',
                'morada' => 'nullable|string|max:255',
                'hora_carga' => 'nullable|date_format:H:i',
                'hora_descarga' => 'nullable|date',
                'total' => 'nullable|numeric',
                'tipo_documento_id' => 'required|exists:tipo_documento,id',
                'cliente_id' => 'required|exists:cliente,id',
            ]);

            $validated['user_id'] = auth()->id();

            $documento = Documento::create($validated);

            return response()->json([
                'success' => true,
                'documento_id' => $documento->id,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeLinhaDocumento(Request $request): JsonResponse
    {

        $validated = $request->validate([
            'observacao' => 'nullable|string|max:255',
            'valor' => 'nullable|numeric',
            'morada' => 'nullable|string|max:255',
            'previsao' => 'required|date',
            'extra' => 'nullable|numeric',
            'documento_id' => 'required|integer|exists:documento,id',
            'linhas' => 'required|array',
            'linhas.*.tipo_palete_id' => 'required|integer|exists:tipo_palete,id',
            'linhas.*.quantidade' => 'required|integer|min:1',
            'linhas.*.artigo_id' => 'required|integer|exists:artigo,id',
        ]);

        DB::beginTransaction();

        try {

            $validated['user_id'] = auth()->id();


            $linhaDocumento = LinhaDocumento::create($validated);

            foreach ($request->input('linhas') as $linha) {
                $linhaDocumento->tipo_palete()->attach(
                    $linha['tipo_palete_id'],
                    [
                        'quantidade' => $linha['quantidade'],
                        'artigo_id' => $linha['artigo_id']
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Linha do documento criada com sucesso.',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar a linha do documento: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro inesperado: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function gerarPDF($id): Response
    {
        // Carrega o documento com suas relações
        $documento = Documento::with(['linha_documento.tipo_palete'])->findOrFail($id);

        // Definir o nome do arquivo PDF
        $nomeArquivo = $documento->tipo_documento->nome . $id . '.pdf';

        // Condicional baseado no tipo_documento_id
        if ($documento->tipo_documento_id == 1) {

            $artigoIds = $documento->linha_documento->flatMap(function ($linha) {
                return $linha->tipo_palete->pluck('pivot.artigo_id');
            });

            $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');

            // Gera o PDF para o tipo_documento_id = 1
            $pdf = Pdf::loadView('pdf.documento', compact('documento', 'artigos'));
        } elseif ($documento->tipo_documento_id == 2) {

            $artigoIds = $documento->linha_documento->flatMap(function ($linha) {
                return $linha->tipo_palete->pluck('pivot.artigo_id');
            });

            $armazemIds = $documento->linha_documento->flatMap(function ($linha) {
                return $linha->tipo_palete->pluck('pivot.armazem_id');
            });

            $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');
            $armazens = Armazem::whereIn('id', $armazemIds)->get()->keyBy('id');

            $pdf = Pdf::loadView('pdf.rececao', compact('documento', 'artigos', 'armazens'));

        } else {
            // Caso queira tratar outros tipos de documentos ou exibir um erro
            abort(404, 'Tipo de documento não suportado');
        }

        // Retorna o download do PDF gerado
        return $pdf->download($nomeArquivo);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Recupera o documento e suas linhas
            $documento = Documento::with(['linha_documento.tipo_palete'])->findOrFail($id);

            // Prepara os dados das linhas
            $linhas = $documento->linha_documento->map(function ($linha) {
                return $linha->tipo_palete->map(function ($tipoPalete) use ($linha) {
                    $artigo = Artigo::find($tipoPalete->pivot->artigo_id);
                    return [
                        'tipo_palete' => $tipoPalete->tipo,
                        'quantidade' => $tipoPalete->pivot->quantidade,
                        'artigo' => $artigo->nome
                    ];
                });
            })->flatten(1);

            return response()->json([
                'success' => true,
                'documento' => $documento,
                'linhas' => $linhas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar os dados: ' . $e->getMessage(),
            ], 500);
        }
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
    public function update(Request $request, $id): JsonResponse
    {
        $userId = Auth::id();

        // Validação dos dados recebidos
        $data = $request->validate([
            'documento' => 'required|array',
            'documento.numero' => 'required|string',
            'documento.data' => 'required|date',
            'linhas' => 'required|array',
            'linhas.*.id' => 'nullable|integer',  // ID da linha_documento
            'linhas.*.observacao' => 'nullable|string|max:255',  // Observação da linha_documento
            'linhas.*.previsao' => 'nullable|date',              // Previsão da linha_documento
            'linhas.*.valor' => 'nullable|numeric',              // Valor da linha_documento
            'linhas.*.tipo_palete' => 'required|integer',        // Dados da pivot
            'linhas.*.quantidade' => 'required|integer',         // Dados da pivot
            'linhas.*.artigo' => 'required|integer'              // Dados da pivot
        ]);

        $documento = Documento::find($id);

        if (!$documento) {
            return response()->json([
                'success' => false,
                'message' => 'Documento não encontrado para atualização'
            ]);
        }

        // Atualizar os campos principais do documento
        $documento->numero = $data['documento']['numero'];
        $documento->data = $data['documento']['data'];
        $documento->user_id = $userId;
        $documento->save();

        $linhasData = collect($data['linhas']);
        $linhasIds = $linhasData->whereNotNull('id')->pluck('id')->toArray();

        // Excluir linhas que não estão mais no formulário
        $documento->linha_documento()->whereNotIn('id', $linhasIds)->delete();

        foreach ($data['linhas'] as $linhaData) {
            if (isset($linhaData['id'])) {
                // Atualizar uma linha existente
                $linha = LinhaDocumento::find($linhaData['id']);
                if ($linha) {
                    // Atualizar os campos da linha diretamente
                    $linha->observacao = $linhaData['observacao'] ?? $linha->observacao;
                    $linha->previsao = $linhaData['previsao'] ?? $linha->previsao;
                    $linha->valor = $linhaData['valor'] ?? $linha->valor;
                    $linha->user_id = $userId;
                    $linha->save();  // Salvar a linha atualizada

                    // Atualizar a relação tipo_palete na tabela pivot
                    $linha->tipo_palete()->sync([
                        $linhaData['tipo_palete'] => [
                            'quantidade' => $linhaData['quantidade'],
                            'artigo_id' => $linhaData['artigo']
                        ]
                    ]);
                }
            } else {
                // Criar uma nova linha
                $novaLinha = $documento->linha_documento()->create([
                    'observacao' => $linhaData['observacao'] ?? null,
                    'previsao' => $linhaData['previsao'] ?? null,
                    'valor' => $linhaData['valor'] ?? null,
                    'user_id' => $userId
                ]);

                // Sincronizar a nova linha com tipo_palete na tabela pivot
                $novaLinha->tipo_palete()->attach($linhaData['tipo_palete'], [
                    'quantidade' => $linhaData['quantidade'],
                    'artigo_id' => $linhaData['artigo']
                ]);
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getArtigosPorCliente($clienteId): \Illuminate\Http\JsonResponse
    {
        $artigos = Artigo::where('cliente_id', $clienteId)->get();

        return response()->json($artigos);
    }

}
