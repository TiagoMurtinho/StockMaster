<?php

namespace App\Http\Controllers;

use App\Models\Artigo;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArtigoController extends Controller
{

    public function index(): Factory|View|Application
    {
        $users = User::all();
        $artigos = Artigo::with('user')->paginate(10);
        return view('pages.admin.artigo.artigo', compact('artigos', 'users'));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nome' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'referencia' => ['required', 'string', 'max:45', 'regex:/^[A-Z0-9]*$/'],
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

    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nome' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'referencia' => ['required', 'string', 'max:45', 'regex:/^[A-Z0-9]*$/'],
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

    public function destroy(string $id): JsonResponse
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

    public function search(Request $request): Application|Factory|View|JsonResponse|RedirectResponse
    {
        $search = $request->input('query');

        if (empty($search)) {
            return redirect()->route('artigo.index');
        }

        if (!preg_match('/^[a-zA-Z0-9\s]*$/', $search)) {
            return response()->json(['error' => 'Pesquisa inválida. Apenas letras, números e espaços são permitidos.'], 400);
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
