<?php

namespace App\Http\Controllers;

use App\Models\Taxa;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxaController extends Controller
{
    public function index(): Factory|View|Application
    {
        $users = User::all();
        $taxas = Taxa::with('user')->paginate(10);
        return view('pages.admin.taxa.taxa', compact('taxas', 'users'));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nome' => ['required', 'string', 'max:45', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'valor' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $taxa = new Taxa();
        $taxa->nome = $request->input('nome');
        $taxa->valor = $request->input('valor');
        $taxa->user_id = auth()->id();
        $taxa->save();

        $taxa->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Taxa criada com sucesso!',
            'data' => $taxa
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nome' => ['required', 'string', 'max:45', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'valor' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $taxa = Taxa::find($id);

        if (!$taxa) {
            return response()->json([
                'success' => false,
                'message' => 'taxa não encontrada!'
            ], 404);
        }

        $taxa->nome = $request->input('nome');
        $taxa->valor = $request->input('valor');
        $taxa->user_id = auth()->id();
        $taxa->save();

        $taxa->load('user');

        return response()->json([
            'success' => true,
            'message' => 'taxa atualizada com sucesso!',
            'data' => $taxa
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $taxa = Taxa::find($id);

        if (!$taxa) {
            return response()->json([
                'success' => false,
                'message' => 'Taxa não encontrada!'
            ], 404);
        }

        $taxa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Taxa eliminado com sucesso!',
            'redirect' => route('taxa.index')
        ]);
    }

    public function search(Request $request): Application|Factory|View|JsonResponse|RedirectResponse
    {
        $search = $request->input('query');

        if (empty($search)) {
            return redirect()->route('taxa.index');
        }

        if (!preg_match('/^[a-zA-Z0-9\s]*$/', $search)) {
            return response()->json(['error' => 'Pesquisa inválida. Apenas letras, números e espaços são permitidos.'], 400);
        }

        $taxas = Taxa::where('nome', 'like', '%' . $search . '%')
            ->with('user')
            ->get();

        if ($request->ajax()) {
            return response()->json($taxas);
        }

        return view('pages.admin.taxa.taxa', compact('taxas'));
    }
}
