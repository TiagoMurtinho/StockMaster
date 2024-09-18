<?php

namespace App\Http\Controllers;

use App\Models\Artigo;
use App\Models\LinhaDocumento;
use App\Models\TipoPalete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LinhaDocumentoController extends Controller
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

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'observacao' => 'required|string|max:255',
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

    public function getArtigosPorCliente($clienteId): \Illuminate\Http\JsonResponse
    {
        $artigos = Artigo::where('cliente_id', $clienteId)->get();

        return response()->json($artigos);
    }
}
