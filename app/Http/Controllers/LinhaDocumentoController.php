<?php

namespace App\Http\Controllers;

use App\Models\LinhaDocumento;
use App\Models\TipoPalete;
use Illuminate\Http\Request;

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

        // Validação dos dados da Linha do Documento
        $validated = $request->validate([
            'quantidade' => 'required|integer|min:1',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'morada' => 'nullable|string|max:255',
            'data_entrega' => 'nullable|date',
            'data_recolha' => 'nullable|date',
            'extra' => 'nullable|numeric',
            'documento_id' => 'required|integer|exists:documento,id',
            'tipo_palete_id' => 'nullable|integer|exists:tipo_palete,id',
            'artigo_id' => 'nullable|integer|exists:artigo,id'
        ]);

        try {
            // Adicionar user_id se necessário
            $validated['user_id'] = auth()->id();

            // Criar a linha do documento
            LinhaDocumento::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Linha do documento criada com sucesso.',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar a linha do documento: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
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
}
