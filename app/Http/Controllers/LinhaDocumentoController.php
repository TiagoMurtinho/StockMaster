<?php

namespace App\Http\Controllers;

use App\Models\Artigo;
use App\Models\Documento;
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
    /*public function update(Request $request, $documentoId)
    {
        // Valida os dados de entrada
        $request->validate([
            'linhas.*.tipo_palete_id' => 'required|exists:tipo_paletes,id',
            'linhas.*.quantidade' => 'required|integer|min:1',
            'linhas.*.artigo_id' => 'required|exists:artigos,id',
        ]);

        // Encontre as linhas relacionadas ao documento
        $documento = Documento::findOrFail($documentoId);
        $linhasExistentes = $documento->linha_documento;

        // Percorre as linhas recebidas da requisição
        foreach ($request->linhas as $linhaData) {
            // Encontra a linha correspondente no banco de dados
            $linha = $linhasExistentes->where('tipo_palete_id', $linhaData['tipo_palete_id'])->first();

            if ($linha) {
                // Atualiza os campos da linha existente
                $linha->update([
                    'quantidade' => $linhaData['quantidade'],
                    'artigo_id' => $linhaData['artigo_id'],
                ]);
            } else {
                // Se não encontrar a linha, cria uma nova (opcional)
                $documento->linha_documento()->create([
                    'tipo_palete_id' => $linhaData['tipo_palete_id'],
                    'quantidade' => $linhaData['quantidade'],
                    'artigo_id' => $linhaData['artigo_id'],
                ]);
            }
        }

        // Retorna uma resposta JSON para o frontend
        return response()->json(['success' => true]);
    }*/

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


}
