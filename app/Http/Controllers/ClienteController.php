<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\TipoPalete;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{

    public function index(): Factory|View|Application
    {
        $users = User::all();
        $clientes = Cliente::with('user')->paginate(10);
        return view('pages.admin.cliente.cliente', compact('clientes', 'users'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nome' => ['required', 'string', 'max:45', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'morada' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'codigo_postal' => ['required', 'string', 'max:8', 'regex:/^[0-9]{4}-[0-9]{3}$/'],
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

    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nome' => ['required', 'string', 'max:45', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'morada' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'codigo_postal' => ['required', 'string', 'max:8', 'regex:/^[0-9]{4}-[0-9]{3}$/'],
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

    public function destroy(string $id): JsonResponse
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

    public function search(Request $request): Application|Factory|View|JsonResponse|RedirectResponse
    {
        $search = $request->input('query');

        if (empty($search)) {
            return redirect()->route('cliente.index');
        }

        if (!preg_match('/^[a-zA-Z0-9\s]*$/', $search)) {
            return response()->json(['error' => 'Pesquisa inválida. Apenas letras, números e espaços são permitidos.'], 400);
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
