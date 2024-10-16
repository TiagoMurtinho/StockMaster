<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\TipoPalete;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ArmazemController extends Controller
{

    public function index(): Factory|View|Application
    {
        $tipoPaletes = TipoPalete::all();
        $users = User::all();
        $armazens = Armazem::with('user', 'tipo_palete')->paginate(10);
        return view('pages.admin.armazem.armazem', compact('armazens', 'users', 'tipoPaletes'));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nome' => ['required', 'string', 'max:45', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'capacidade' => 'required|numeric',
            'tipo_palete_id' => 'required|exists:tipo_palete,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $armazem = new Armazem();
        $armazem->nome = $request->input('nome');
        $armazem->capacidade = $request->input('capacidade');
        $armazem->tipo_palete_id = $request->input('tipo_palete_id');
        $armazem->user_id = auth()->id();
        $armazem->save();

        $armazem->load('tipo_palete', 'user');

        return response()->json([
            'success' => true,
            'message' => 'Armazem criado com sucesso!',
            'data' => $armazem
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nome' => ['required', 'string', 'max:45', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'capacidade' => 'required|numeric',
            'tipo_palete_id' => 'required|exists:tipo_palete,id'
        ]);

        $armazem = Armazem::find($id);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $armazem->nome = $request->input('nome');
        $armazem->capacidade = $request->input('capacidade');
        $armazem->tipo_palete_id = $request->input('tipo_palete_id');
        $armazem->user_id = auth()->id();
        $armazem->save();

        $armazem->load('tipo_palete', 'user');

        return response()->json([
            'success' => true,
            'message' => 'Armazem criado com sucesso!',
            'data' => $armazem
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $armazem = Armazem::find($id);

        if (!$armazem) {
            return response()->json([
                'success' => false,
                'message' => 'Armazém não encontrado!'
            ], 404);
        }

        $armazem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Armazém eliminado com sucesso!',
            'redirect' => route('armazem.index')
        ]);
    }

    public function search(Request $request): Application|Factory|View|JsonResponse|RedirectResponse
    {
        $search = $request->input('query');

        if (empty($search)) {
            return redirect()->route('armazem.index');
        }

        if (!preg_match('/^[a-zA-Z0-9\s]*$/', $search)) {
            return response()->json(['error' => 'Pesquisa inválida. Apenas letras, números e espaços são permitidos.'], 400);
        }

        $armazens = Armazem::where('nome', 'like', '%' . $search . '%')
            ->orWhereHas('tipo_palete', function ($query) use ($search) {
                $query->where('tipo', 'like', '%' . $search . '%');
            })
            ->with('tipo_palete', 'user')
            ->get();

        if ($request->ajax()) {
            return response()->json($armazens);
        }

        return view('pages.admin.armazem.armazem', compact('armazens'));
    }
}
