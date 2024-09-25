<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\Artigo;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\LinhaDocumento;
use App\Models\DocumentoTipoPalete;
use App\Models\Taxa;
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
        \Log::info('Dados recebidos:', $request->all());

        try {
            // Validação
            $validated = $request->validate([
                'documento.numero' => 'required|numeric',
                'documento.matricula' => 'nullable|string|max:45',
                'documento.morada' => 'nullable|string|max:255',
                'documento.total' => 'nullable|numeric',
                'documento.observacao' => 'nullable|string|max:255',
                'documento.previsao' => 'required|date',
                'documento.extra' => 'nullable|numeric',
                'documento.taxa_id' => 'required|integer|exists:taxa,id',
                'documento.tipo_documento_id' => 'required|exists:tipo_documento,id',
                'documento.cliente_id' => 'required|exists:cliente,id',
                'linhas' => 'required|array',
                'linhas.*.tipo_palete_id' => 'required|integer|exists:tipo_palete,id',
                'linhas.*.quantidade' => 'required|integer|min:1',
                'linhas.*.artigo_id' => 'required|integer|exists:artigo,id',
            ]);

            // Criação do documento
            $documentoData = $validated['documento'];
            $documentoData['user_id'] = auth()->id();
            $documentoData['data'] = now(); // Adiciona a data atual ao campo 'data'

            $documento = Documento::create($documentoData);

            // Criação das linhas associadas (tipo_palete, quantidade, artigo_id)
            foreach ($request->input('linhas') as $linha) {
                $documento->tipo_palete()->attach(
                    $linha['tipo_palete_id'],
                    [
                        'quantidade' => $linha['quantidade'],
                        'artigo_id' => $linha['artigo_id']
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'documento_id' => $documento->id,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Resposta em caso de erro de validação
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Resposta em caso de erro geral
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function gerarPDF($id): Response
    {
        // Carrega o documento com suas relações
        $documento = Documento::with(['tipo_palete'])->findOrFail($id);

        $nomeArquivo = $documento->tipo_documento->nome . $id . '.pdf';

        if ($documento->tipo_documento_id == 1) {

            $artigoIds = $documento->tipo_palete->pluck('pivot.artigo_id');

            $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');

            $pdf = Pdf::loadView('pdf.documento', compact('documento', 'artigos'));
        } elseif ($documento->tipo_documento_id == 2) {

            $artigoIds = $documento->tipo_palete->pluck('pivot.artigo_id');

            $armazemIds = $documento->tipo_palete->pluck('pivot.armazem_id');

            $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');
            $armazens = Armazem::whereIn('id', $armazemIds)->get()->keyBy('id');

            $pdf = Pdf::loadView('pdf.rececao', compact('documento', 'artigos', 'armazens'));

        } elseif ($documento->tipo_documento_id == 3) {

            $artigoIds = $documento->tipo_palete->pluck('pivot.artigo_id');

            $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');

            $pdf = Pdf::loadView('pdf.pedido-retirada', compact('documento', 'artigos'));

        } elseif ($documento->tipo_documento_id == 4) {


            $artigoIds = $documento->tipo_palete->pluck('pivot.artigo_id');

            $armazemIds = $documento->tipo_palete->pluck('pivot.armazem_id');

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
            $documento = Documento::with(['tipo_palete' => function($query) {
                $query->withPivot('quantidade', 'artigo_id', 'id');
            }])->findOrFail($id);

            $linhas = $documento->tipo_palete->map(function ($tipoPalete) {
                $artigo = Artigo::find($tipoPalete->pivot->artigo_id);
                return [
                    'tipo_palete' => $tipoPalete->tipo,
                    'quantidade' => $tipoPalete->pivot->quantidade,
                    'artigo' => $artigo ? $artigo->nome : null,
                    'pivot_id' => $tipoPalete->pivot->id
                ];
            });

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
                'documento.observacao' => 'nullable|string|max:255',
                'documento.previsao' => 'nullable|date',
                'documento.taxa_id' => 'nullable|integer',
                'documento_tipo_palete' => 'required|array',
                'documento_tipo_palete.*.documento_id' => 'required|integer',
                'documento_tipo_palete.*.tipo_palete' => 'required|string',
                'documento_tipo_palete.*.quantidade' => 'required|integer',
                'documento_tipo_palete.*.artigo' => 'required|string',
                'documento_tipo_palete.*.deleted' => 'boolean',
                'documento_tipo_palete.*.id' => 'nullable|integer',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $e->errors()
            ], 400);
        }

        $documento = Documento::find($id);

        if (!$documento) {
            return response()->json([
                'success' => false,
                'message' => 'Documento não encontrado para atualização'
            ]);
        }

        $documento->numero = $data['documento']['numero'];
        $documento->data = $data['documento']['data'];
        $documento->observacao = $data['documento']['observacao'] ?? $documento->observacao;
        $documento->previsao = $data['documento']['previsao'] ?? $documento->previsao;
        $documento->taxa_id = $data['documento']['taxa_id'] ?? $documento->taxa_id;
        $documento->user_id = $userId;
        $documento->save();

        foreach ($data['documento_tipo_palete'] as $linhaData) {

            if (isset($linhaData['id'])) {

                if (isset($linhaData['deleted']) && $linhaData['deleted'] === true) {
                    DocumentoTipoPalete::where('id', $linhaData['id'])->delete();

                } else {

                    $linhaTipoPalete = DocumentoTipoPalete::find($linhaData['id']);
                    if ($linhaTipoPalete) {
                        $linhaTipoPalete->quantidade = $linhaData['quantidade'];
                        $linhaTipoPalete->tipo_palete_id = $linhaData['tipo_palete'];
                        $linhaTipoPalete->artigo_id = $linhaData['artigo'];
                        $linhaTipoPalete->save();
                    }
                }
            } else {
                DocumentoTipoPalete::create([
                    'documento_id' => $linhaData['documento_id'],
                    'tipo_palete_id' => $linhaData['tipo_palete'],
                    'artigo_id' => $linhaData['artigo'],
                    'quantidade' => $linhaData['quantidade']
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

            $documento = Documento::find($id);

            if (!$documento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Documento não encontrado para remoção'
                ], 404);
            }

            DocumentoTipoPalete::where('documento_id', $documento->id)->delete();

            $documento->delete();

            return response()->json([
                'success' => true,
                'message' => 'Documento removido com sucesso!',
                'redirect' => route('documento.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover documento'
            ], 500);
        }
    }

    public function getArtigosPorCliente($clienteId): JsonResponse
    {
        $artigos = Artigo::where('cliente_id', $clienteId)->get();

        return response()->json($artigos);
    }

}
