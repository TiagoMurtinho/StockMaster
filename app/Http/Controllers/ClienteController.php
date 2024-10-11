<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\TipoPalete;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $users = User::all();
        $clientes = Cliente::with('user')->paginate(10);
        return view('pages.admin.cliente.cliente', compact('clientes', 'users'));
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
            'nome' => 'required|string|max:45',
            'morada' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:8',
            'nif' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $cliente = new Cliente();
        $cliente->nome = $request->input('nome');
        $cliente->morada = $request->input('morada');
        $cliente->codigo_postal = $request->input('codigo_postal');
        $cliente->nif = $request->input('nif');
        $cliente->user_id = auth()->id();
        $cliente->save();

        $cliente->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Cliente criado com sucesso!',
            'data' => $cliente
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
            'nome' => 'required|string|max:45',
            'morada' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:8',
            'nif' => 'required|numeric',
        ]);

        $cliente = Cliente::find($id);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $cliente->nome = $request->input('nome');
        $cliente->morada = $request->input('morada');
        $cliente->codigo_postal = $request->input('codigo_postal');
        $cliente->nif = $request->input('nif');
        $cliente->user_id = auth()->id();
        $cliente->save();

        $cliente->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Cliente alterado com sucesso!',
            'data' => $cliente
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente não encontrado!'
            ], 404);
        }

        $cliente->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado com sucesso!',
            'redirect' => route('cliente.index')
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('query');

        if (empty($search)) {
            return redirect()->route('cliente.index');
        }

        $clientes = Cliente::with('user')
            ->where('nome', 'like', '%' . $search . '%')
            ->orWhere('nif', 'like', '%' . $search . '%')
            ->get();

        if ($request->ajax()) {
            return response()->json($clientes);
        }

        return view('pages.admin.cliente.cliente', compact('clientes'));
    }
}
