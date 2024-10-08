<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\Artigo;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArtigoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();
        $users = User::all();
        $artigos = Artigo::with('user', 'cliente')->paginate(10);
        return view('pages.admin.artigo.artigo', compact('artigos', 'users', 'clientes'));
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
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:100',
            'referencia' => 'required|string|max:45',
            'cliente_id' => 'required|exists:cliente,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $artigo = new Artigo();
        $artigo->nome = $request->input('nome');
        $artigo->referencia = $request->input('referencia');
        $artigo->cliente_id = $request->input('cliente_id');
        $artigo->user_id = auth()->id();
        $artigo->save();

        $artigo->load('cliente', 'user');

        return response()->json([
            'success' => true,
            'message' => 'Artigo criado com sucesso!',
            'data' => $artigo
        ]);
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
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:100',
            'referencia' => 'required|string|max:45',
            'cliente_id' => 'required|exists:cliente,id'
        ]);

        $artigo = Artigo::find($id);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $artigo->nome = $request->input('nome');
        $artigo->referencia = $request->input('referencia');
        $artigo->cliente_id = $request->input('cliente_id');
        $artigo->user_id = auth()->id();
        $artigo->save();

        $artigo->load('cliente', 'user');

        return response()->json([
            'success' => true,
            'message' => 'Artigo criado com sucesso!',
            'data' => $artigo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $artigo = Artigo::find($id);

        if (!$artigo) {
            return response()->json([
                'success' => false,
                'message' => 'Artigo não encontrado!'
            ], 404);
        }

        $artigo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artigo eliminado com sucesso!',
            'redirect' => route('artigo.index')
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('query');

        if (empty($search)) {
            return redirect()->route('artigo.index');
        }

        $artigos = Artigo::where('nome', 'like', '%' . $search . '%')
            ->orWhere('referencia', 'like', '%' . $search . '%')
            ->orWhereHas('cliente', function ($query) use ($search) {
                $query->where('nome', 'like', '%' . $search . '%');
            })
            ->with('cliente', 'user')
            ->get();

        if ($request->ajax()) {
        return response()->json($artigos);
        }

        return view('pages.admin.artigo.artigo', compact('artigos'));
    }
}
