<?php

namespace App\Http\Controllers;

use App\Models\Palete;
use Illuminate\Http\Request;

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
        // Validação dos dados recebidos
        $request->validate([
            'linha_documento_id' => 'required|exists:linha_documento,id',
            'localizacao' => 'required|array',
            'tipo_palete_id' => 'required|array',
            'artigo_id' => 'required|array',
        ]);

        // Pega o `linha_documento_id`
        $linhaDocumentoId = $request->input('linha_documento_id');

        // Itera sobre os tipos de paletes e localizações
        foreach ($request->localizacao as $tipoPaleteId => $localizacoes) {
            $tipoPalete = $request->input('tipo_palete_id.' . $tipoPaleteId);

            if ($tipoPalete) {
                $artigoIds = $request->input('artigo_id.' . $tipoPaleteId);

                foreach ($localizacoes as $index => $localizacao) {
                    // Obtém o artigo_id correspondente ao índice
                    $artigoId = $artigoIds[$index] ?? null;

                    // Cria uma nova entrada na tabela `palete`
                    Palete::create([
                        'linha_documento_id' => $linhaDocumentoId,
                        'localizacao' => $localizacao,
                        'data_entrada' => now(),
                        'tipo_palete_id' => $tipoPalete,
                        'artigo_id' => $artigoId,
                        'user_id' => auth()->id(),
                    ]);
                }
            }
        }

        return response()->json();
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
