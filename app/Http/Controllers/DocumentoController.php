<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\Artigo;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\LinhaDocumento;
use App\Models\DocumentoTipoPalete;
use App\Models\Palete;
use App\Models\Taxa;
use App\Models\TipoDocumento;
use App\Models\TipoPalete;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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
        $tiposDocumento = TipoDocumento::whereIn('id', [1, 3, 5])->get();
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
            $rules = [
                'documento.numero' => 'required|numeric',
                'documento.matricula' => 'nullable|string|max:45',
                'documento.morada' => 'nullable|string|max:255',
                'documento.total' => 'nullable|numeric',
                'documento.observacao' => 'nullable|string|max:255',
                'documento.extra' => 'nullable|numeric',
                'documento.tipo_documento_id' => 'required|exists:tipo_documento,id',
                'documento.cliente_id' => 'required|exists:cliente,id',
            ];

            if ($request->input('documento.tipo_documento_id') != 5) {
                $rules['documento.previsao'] = 'required|date';
                $rules['documento.taxa_id'] = 'required|integer|exists:taxa,id';
                $rules['linhas'] = 'required|array';
                $rules['linhas.*.tipo_palete_id'] = 'required|integer|exists:tipo_palete,id';
                $rules['linhas.*.quantidade'] = 'required|integer|min:1';
                $rules['linhas.*.artigo_id'] = 'required|integer|exists:artigo,id';
            }

            $validated = $request->validate($rules);

            $documentoData = $validated['documento'];
            $documentoData['user_id'] = auth()->id();
            $documentoData['data'] = now();

            $documento = Documento::create($documentoData);

            if (isset($validated['linhas'])) {
                foreach ($validated['linhas'] as $linha) {
                    $documento->tipo_palete()->attach(
                        $linha['tipo_palete_id'],
                        [
                            'quantidade' => $linha['quantidade'],
                            'artigo_id' => $linha['artigo_id']
                        ]
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Documento inserido com sucesso!',
                'documento_id' => $documento->id,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            $documentoErrors = [];
            $linhasErrors = [];

            foreach ($e->errors() as $field => $errorMessages) {
                if (str_starts_with($field, 'documento')) {
                    $documentoErrors[$field] = $errorMessages;
                } elseif (str_starts_with($field, 'linhas')) {
                    $linhasErrors[$field] = $errorMessages;
                }
            }

            return response()->json([
                'success' => false,
                'errors' => [
                    'documento' => $documentoErrors,
                    'linhas' => $linhasErrors,
                ],
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function gerarPDF($id): Response
    {

        $documento = Documento::with(['tipo_palete'])->findOrFail($id);
        $nomeArquivo = $documento->tipo_documento->nome . $id . '.pdf';
        $armazemIds = $documento->tipo_palete->pluck('pivot.armazem_id');
        $artigoIds = $documento->tipo_palete->pluck('pivot.artigo_id');
        $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');

        switch ($documento->tipo_documento_id) {
            case 1:
                $pdf = Pdf::loadView('pdf.documento', compact('documento', 'artigos'));
                break;

            case 2:
                $armazens = Armazem::whereIn('id', $armazemIds)->get()->keyBy('id');
                $pdf = Pdf::loadView('pdf.rececao', compact('documento', 'artigos', 'armazens'));
                break;

            case 3:
                $pdf = Pdf::loadView('pdf.pedido-retirada', compact('documento', 'artigos'));
                break;

            case 4:
                $armazens = Armazem::whereIn('id', $armazemIds)->get()->keyBy('id');
                $pdf = Pdf::loadView('pdf.guia-transporte', compact('documento', 'artigos', 'armazens'));
                break;

            case 5:
                $documentoTipo2 = Documento::where('cliente_id', $documento->cliente_id)
                    ->where('tipo_documento_id', 2)
                    ->first();

                $paletes = collect();
                if ($documentoTipo2) {
                    $paletes = Palete::where('documento_id', $documentoTipo2->id)->get();
                }

                $artigoPaleteIds = $paletes->pluck('artigo_id')->filter();
                $artigos = Artigo::whereIn('id', $artigoPaleteIds)->get()->keyBy('id');
                $pdf = Pdf::loadView('pdf.faturacao', compact('documento', 'artigos', 'paletes'));
                break;

            default:
                throw new \Exception("Tipo de documento não reconhecido.");
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

        return response()->json([
            'success' => true,
            'message' => 'Documento atualizado com sucesso!',
        ]);
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

    public function faturacao($clienteId)
    {
        $cliente = Cliente::find($clienteId);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente não encontrado.'], 404);
        }

        $documentoTipo2 = Documento::where('cliente_id', $clienteId)
            ->where('tipo_documento_id', 2)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$documentoTipo2) {
            return response()->json(['error' => 'Nenhum documento do tipo 2 encontrado para este cliente.'], 404);
        }

        $paletes = Palete::where('documento_id', $documentoTipo2->id)->get();
        $total = 0;

        $dataInicio = request()->input('data_inicio');
        $dataFim = request()->input('data_fim');

        $dataInicioCarbon = $dataInicio ? Carbon::parse($dataInicio) : null;
        $dataFimCarbon = $dataFim ? Carbon::parse($dataFim) : null;

        foreach ($paletes as $palete) {
            $tipoPalete = $palete->tipo_palete;
            $valorDiario = $tipoPalete->valor;
            $dataEntrada = Carbon::parse($palete->data_entrada)->startOfDay(); // Começo do dia
            $dataSaida = $palete->data_saida ? Carbon::parse($palete->data_saida)->startOfDay() : Carbon::now()->startOfDay();

            if ($dataInicioCarbon) {
                if ($dataInicioCarbon->greaterThan($dataEntrada)) {
                    $inicioFaturamento = $dataInicioCarbon->startOfDay();
                } else {
                    $inicioFaturamento = $dataEntrada;
                }
            } else {
                $inicioFaturamento = $dataEntrada;
            }

            $dataFinalFaturamento = $dataFimCarbon ? $dataFimCarbon->startOfDay() : $dataSaida;

            if ($dataFinalFaturamento->greaterThan($dataSaida)) {
                $dataFinalFaturamento = $dataSaida;
            }

            if ($dataFinalFaturamento->greaterThanOrEqualTo($inicioFaturamento)) {

                $dias = $inicioFaturamento->diffInDays($dataFinalFaturamento) + 1;

                $dias = max($dias, 1);

                $totalPalete = $dias * $valorDiario;
                $total += $totalPalete;

            }
        }

        $documentos = Documento::where('cliente_id', $clienteId)
            ->whereIn('tipo_documento_id', [2, 4])
            ->get();

        foreach ($documentos as $documento) {
            $taxa = Taxa::find($documento->taxa_id);
            if ($taxa) {
                $total += $taxa->valor;
            }
        }

        return response()->json([
            'total' => $total,
            'paletes' => $paletes,
        ]);
    }

}
