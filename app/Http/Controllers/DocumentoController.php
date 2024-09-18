<?php

namespace App\Http\Controllers;

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

    public function gerarPDF($id): Response
    {

        $documento = Documento::with(['linha_documento.tipo_palete'])->findOrFail($id);

        $nomeArquivo = $documento->tipo_documento->nome . $id . '.pdf';

        $artigoIds = $documento->linha_documento->flatMap(function ($linha) {
            return $linha->tipo_palete->pluck('pivot.artigo_id');
        });

        $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');

        $pdf = Pdf::loadView('pdf.documento', compact('documento', 'artigos'));

        return $pdf->download($nomeArquivo);
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
