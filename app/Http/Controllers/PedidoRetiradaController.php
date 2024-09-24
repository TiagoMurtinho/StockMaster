<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\Artigo;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\Palete;
use App\Models\TipoDocumento;
use App\Models\TipoPalete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoRetiradaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Obtém todos os documentos pendentes
        $documentos = Documento::with(['linha_documento.tipo_palete'])
            ->where('tipo_documento_id', 3)
            ->where('estado', 'pendente')
            ->whereHas('linha_documento', function ($query) {
                $query->orderBy('data_entrada', 'asc');
            })
            ->get();

        $artigoIds = $documentos->flatMap(function ($documento) {
            return $documento->linha_documento->flatMap(function ($linha) {
                return $linha->tipo_palete->pluck('pivot.artigo_id'); // Acessando artigo_id da tabela pivot
            });
        });

        $artigos = Artigo::whereIn('id', $artigoIds)->get()->keyBy('id');
        $paletes = [];
        $quantidadesPaletes = [];

        foreach ($documentos as $documento) {
            $documentoId = $documento->id;

            $paletes[$documentoId] = DB::select("
    SELECT p.*
    FROM palete p
    WHERE p.linha_documento_id IN (
        SELECT ldtp.linha_documento_id
        FROM linha_documento_tipo_palete ldtp
        WHERE ldtp.artigo_id IN (
            SELECT ldtp2.artigo_id
            FROM linha_documento_tipo_palete ldtp2
            WHERE ldtp2.linha_documento_id IN (
                SELECT ld.id
                FROM linha_documento ld
                JOIN documento d ON ld.documento_id = d.id
                WHERE d.tipo_documento_id = 3 AND d.estado = 'pendente'
            )
        )
        AND ldtp.tipo_palete_id IN (
            SELECT ldtp3.tipo_palete_id
            FROM linha_documento_tipo_palete ldtp3
            WHERE ldtp3.linha_documento_id IN (
                SELECT ld.id
                FROM linha_documento ld
                JOIN documento d ON ld.documento_id = d.id
                WHERE d.tipo_documento_id = 3 AND d.estado = 'pendente'
            )
        )
    )
    AND EXISTS (
        SELECT 1
        FROM linha_documento ld
        JOIN documento d ON ld.documento_id = d.id
        WHERE ld.id = p.linha_documento_id
        AND d.cliente_id = (SELECT d2.cliente_id FROM documento d2 WHERE d2.id = ? LIMIT 1)
    )
    AND p.tipo_palete_id IN (
        SELECT ldtp4.tipo_palete_id
        FROM linha_documento_tipo_palete ldtp4
        WHERE ldtp4.linha_documento_id IN (
            SELECT ld5.id
            FROM linha_documento ld5
            WHERE ld5.documento_id = ?
        )
    );
", [$documentoId, $documentoId]);

            // Converte o resultado em uma coleção para facilitar o uso
            $paletes[$documentoId] = collect($paletes[$documentoId]);

            $quantidadesPaletes[$documentoId] = DB::select("
            SELECT ldtp.artigo_id, COUNT(p.id) AS numero_paletes
            FROM linha_documento_tipo_palete ldtp
            JOIN palete p ON p.linha_documento_id = ldtp.linha_documento_id
            WHERE ldtp.linha_documento_id IN (
                SELECT ld.id
                FROM linha_documento ld
                WHERE ld.documento_id = ?
            )
            GROUP BY ldtp.artigo_id
        ", [$documentoId]);

            $documento->min_data_entrada = $paletes[$documentoId]->min('data_entrada');

            $paletes[$documentoId] = Palete::with(['artigo', 'tipo_palete'])->get();
        }

        $documentos = $documentos->sortBy('min_data_entrada');

        $armazens = Armazem::all();
        $tiposDocumento = TipoDocumento::all();
        $clientes = Cliente::all();
        $tipoPaletes = TipoPalete::all();

        return view('pages.pedido.pedido-retirada.pedido-retirada', compact('documentos', 'tiposDocumento', 'clientes', 'tipoPaletes', 'armazens', 'paletes', 'quantidadesPaletes', 'artigos'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
/*
        $documento = Documento::with(['linha_documento.tipo_palete'])->findOrFail($id);

        if (!$documento) {
            return response()->json(['error' => 'Documento não encontrado.'], 404);
        }

        $paletes = DB::select("
        SELECT p.*
        FROM palete p
        WHERE p.linha_documento_id IN (
            SELECT ldtp.linha_documento_id
            FROM linha_documento_tipo_palete ldtp
            WHERE ldtp.artigo_id IN (
                SELECT ldtp2.artigo_id
                FROM linha_documento_tipo_palete ldtp2
                WHERE ldtp2.linha_documento_id IN (
                    SELECT ld.id
                    FROM linha_documento ld
                    JOIN documento d ON ld.documento_id = d.id
                    WHERE d.tipo_documento_id = 3 AND d.estado = 'pendente'
                )
            )
            AND ldtp.tipo_palete_id IN (
                SELECT ldtp3.tipo_palete_id
                FROM linha_documento_tipo_palete ldtp3
                WHERE ldtp3.linha_documento_id IN (
                    SELECT ld.id
                    FROM linha_documento ld
                    JOIN documento d ON ld.documento_id = d.id
                    WHERE d.tipo_documento_id = 3 AND d.estado = 'pendente'
                )
            )
        )
        AND (SELECT d.cliente_id
             FROM documento d
             WHERE d.id = ? LIMIT 1) =
        (SELECT d.cliente_id
         FROM documento d
         WHERE d.id = (SELECT ld.documento_id FROM linha_documento ld WHERE ld.id = p.linha_documento_id LIMIT 1));
    ", [$id]);

        $paletes = collect($paletes);

        return view('pages.pedido.pedido-retirada.modals.retirada-modal', compact('documento', 'paletes'));*/
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
