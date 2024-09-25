<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\Artigo;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\LinhaDocumento;
use App\Models\LinhaDocumentoTipoPalete;
use App\Models\Taxa;
use App\Models\TipoDocumento;
use App\Models\TipoPalete;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {

        $documentos = Documento::all();

        $tiposDocumento = TipoDocumento::whereIn('id', [1, 3])->get();
        $clientes = Cliente::all();
        $tipoPaletes = TipoPalete::all();
        $taxas = Taxa::all();
        return view('pages.admin.documento.documento', compact('documentos', 'tiposDocumento', 'clientes', 'tipoPaletes', 'taxas'));
    }

    public function indexJson()
    {
        return response()->json(Documento::with('tipo_documento', 'cliente', 'user')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $tiposDocumentos = TipoDocumento::all();
        $clientes = Cliente::all();
        $taxas = Taxa::all();

        return view('pages.documento.documento', compact('tiposDocumentos', 'clientes', 'taxas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'numero' => 'required|numeric',
                'matricula' => 'nullable|string|max:45',
                'morada' => 'nullable|string|max:255',
                'hora_carga' => 'nullable|date_format:H:i',
                'hora_descarga' => 'nullable|date',
                'total' => 'nullable|numeric',
                'tipo_documento_id' => 'required|exists:tipo_documento,id',
                'cliente_id' => 'required|exists:cliente,id',
            ]);

            $validated['user_id'] = auth()->id();
            $validated['data'] = now();

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
            'morada' => 'nullable|string|max:255',
            'previsao' => 'required|date',
            'extra' => 'nullable|numeric',
            'taxa_id' => 'required|integer|exists:taxa,id',
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

        } elseif ($documento->tipo_documento_id == 3) {

            $artigoIds = $documento->linha_documento->flatMap(function ($linha) {
                return $linha->tipo_palete->pluck('pivot.artigo_id');
            });

            $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');

            $pdf = Pdf::loadView('pdf.pedido-retirada', compact('documento', 'artigos'));

        } elseif ($documento->tipo_documento_id == 4) {


            $artigoIds = $documento->linha_documento->flatMap(function ($linha) {
                return $linha->tipo_palete->pluck('pivot.artigo_id');
            });

            $armazemIds = $documento->linha_documento->flatMap(function ($linha) {
                return $linha->tipo_palete->pluck('pivot.armazem_id');
            });

            $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');
            $armazens = Armazem::whereIn('id', $armazemIds)->get()->keyBy('id');

            $pdf = Pdf::loadView('pdf.guia-transporte', compact('documento', 'artigos', 'armazens'));

        }

        return $pdf->download($nomeArquivo);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {

            $documento = Documento::with(['linha_documento.tipo_palete' => function($query) {
                $query->withPivot('id', 'quantidade', 'artigo_id'); // Inclua o ID da pivot
            }])->findOrFail($id);

            // Prepara os dados das linhas
            $linhas = $documento->linha_documento->map(function ($linha) {
                return $linha->tipo_palete->map(function ($tipoPalete) use ($linha) {
                    $artigo = Artigo::find($tipoPalete->pivot->artigo_id);
                    return [
                        'tipo_palete' => $tipoPalete->tipo,
                        'quantidade' => $tipoPalete->pivot->quantidade,
                        'artigo' => $artigo->nome,
                        'pivot_id' => $tipoPalete->pivot->id
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

        try {
            $data = $request->validate([
                'documento' => 'required|array',
                'documento.numero' => 'required|string',
                'documento.data' => 'required|date',
                'linha_documento' => 'required|array',
                'linha_documento.id' => 'required|integer',
                'linha_documento.observacao' => 'nullable|string|max:255',
                'linha_documento.previsao' => 'nullable|date',
                'linha_documento.taxa_id' => 'nullable|integer',
                'linha_documento_tipo_palete' => 'required|array',
                'linha_documento_tipo_palete.*.linha_documento_id' => 'required|integer',
                'linha_documento_tipo_palete.*.tipo_palete' => 'required|string',
                'linha_documento_tipo_palete.*.quantidade' => 'required|integer',
                'linha_documento_tipo_palete.*.artigo' => 'required|string',
                'linha_documento_tipo_palete.*.deleted' => 'boolean',
                'linha_documento_tipo_palete.*.id' => 'nullable|integer', // ID opcional
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $e->errors()
            ], 400);
        }

        \Log::info('Dados recebidos para atualização', ['data' => $data]);

        $documento = Documento::find($id);

        if (!$documento) {
            \Log::error('Documento não encontrado', ['documento_id' => $id]);
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

        \Log::info('Documento atualizado', ['documento_id' => $documento->id]);

        $linhaDocumento = LinhaDocumento::find($data['linha_documento']['id']);

        if ($linhaDocumento) {
            $linhaDocumento->observacao = $data['linha_documento']['observacao'] ?? $linhaDocumento->observacao;
            $linhaDocumento->previsao = $data['linha_documento']['previsao'] ?? $linhaDocumento->previsao;
            $linhaDocumento->taxa_id = $data['linha_documento']['taxa_id'] ?? $linhaDocumento->taxa_id;
            $linhaDocumento->save();

            \Log::info('Linha documento atualizada', ['linha_documento_id' => $linhaDocumento->id]);
        } else {
            \Log::error('Linha documento não encontrada', ['linha_documento_id' => $data['linha_documento']['id']]);
        }

        foreach ($data['linha_documento_tipo_palete'] as $linhaData) {
            // Verifica se o ID está presente
            if (isset($linhaData['id'])) {
                // Se a linha foi marcada como deletada
                if (isset($linhaData['deleted']) && $linhaData['deleted'] === true) {
                    LinhaDocumentoTipoPalete::where('id', $linhaData['id'])->delete();
                    \Log::info('Entrada removida da tabela LinhaDocumentoTipoPalete', ['pivot_id' => $linhaData['id']]);
                } else {
                    // Atualiza a linha existente
                    $linhaTipoPalete = LinhaDocumentoTipoPalete::find($linhaData['id']);
                    if ($linhaTipoPalete) {
                        $linhaTipoPalete->quantidade = $linhaData['quantidade'];
                        $linhaTipoPalete->tipo_palete_id = $linhaData['tipo_palete'];
                        $linhaTipoPalete->artigo_id = $linhaData['artigo'];
                        $linhaTipoPalete->save();
                        \Log::info('Tabela pivot atualizada', ['pivot_id' => $linhaTipoPalete->id]);
                    } else {
                        \Log::warning('Tabela pivot não encontrada para atualização', ['pivot_id' => $linhaData['id']]);
                    }
                }
            } else {
                // Cria uma nova entrada se o ID não estiver presente
                LinhaDocumentoTipoPalete::create([
                    'linha_documento_id' => $linhaData['linha_documento_id'],
                    'tipo_palete_id' => $linhaData['tipo_palete'],
                    'artigo_id' => $linhaData['artigo'],
                    'quantidade' => $linhaData['quantidade']
                ]);
                \Log::info('Nova entrada adicionada à tabela pivot', [
                    'linha_documento_id' => $linhaData['linha_documento_id'],
                    'tipo_palete_id' => $linhaData['tipo_palete'],
                    'artigo_id' => $linhaData['artigo'],
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $userId = Auth::id();

        try {
            // Localiza o documento
            $documento = Documento::find($id);

            if (!$documento) {
                \Log::error('Documento não encontrado', ['documento_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Documento não encontrado para remoção'
                ], 404);
            }

            // Remove as linhas relacionadas
            LinhaDocumento::where('documento_id', $id)->delete();
            LinhaDocumentoTipoPalete::where('linha_documento_id', $documento->linha_documento_id)->delete();

            // Remove o documento
            $documento->delete();

            \Log::info('Documento removido com sucesso', ['documento_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Documento removido com sucesso!',
                'redirect' => route('documento.index')
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao remover documento', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover documento'
            ], 500);
        }
    }

    public function getArtigosPorCliente($clienteId): \Illuminate\Http\JsonResponse
    {
        $artigos = Artigo::where('cliente_id', $clienteId)->get();

        return response()->json($artigos);
    }

}
