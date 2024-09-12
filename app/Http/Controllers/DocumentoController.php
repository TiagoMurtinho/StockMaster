<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Documento;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pega os documentos do banco de dados
        $documentos = Documento::all();
        $tiposDocumento = TipoDocumento::all();
        $clientes = Cliente::all();
        return view('pages.documento.documento', compact('documentos', 'tiposDocumento', 'clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposDocumentos = TipoDocumento::all();
        $clientes = Cliente::all();

        return view('pages.documento.documento', compact('tiposDocumentos', 'clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
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

        Documento::create($validatedData);

        return redirect()->route('documentos.index')->with('success', 'Documento criado com sucesso.');
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
