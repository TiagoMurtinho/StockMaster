<?php

namespace App\Http\Controllers;

use App\Models\TipoPalete;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoPaleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|View|Application
    {
        $users = User::all();
        $tipoPaletes = TipoPalete::with('user')->paginate(10);
        return view('pages.admin.tipo-palete.tipo-palete', compact('tipoPaletes', 'users'));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tipo' => ['required', 'string', 'max:45', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'valor' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $tipoPalete = new TipoPalete();
        $tipoPalete->tipo = $request->input('tipo');
        $tipoPalete->valor = $request->input('valor');
        $tipoPalete->user_id = auth()->id();
        $tipoPalete->save();

        $tipoPalete->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Tipo de palete criado com sucesso!',
            'data' => $tipoPalete
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'tipo' => ['required', 'string', 'max:45', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'valor' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $tipoPalete = TipoPalete::find($id);

        if (!$tipoPalete) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de palete não encontrado!'
            ], 404);
        }

        $tipoPalete->tipo = $request->input('tipo');
        $tipoPalete->valor = $request->input('valor');
        $tipoPalete->user_id = auth()->id();
        $tipoPalete->save();

        $tipoPalete->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Tipo de palete atualizado com sucesso!',
            'data' => $tipoPalete
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $tipoPalete = TipoPalete::find($id);

        if (!$tipoPalete) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de palete não encontrado!'
            ], 404);
        }

        $tipoPalete->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de palete eliminado com sucesso!',
            'redirect' => route('tipo-palete.index')
        ]);
    }

    public function search(Request $request): Application|Factory|View|JsonResponse|RedirectResponse
    {

        $search = $request->input('query');

        if (empty($search)) {
            return redirect()->route('tipo-palete.index');
        }

        if (!preg_match('/^[a-zA-Z0-9\s]*$/', $search)) {
            return response()->json(['error' => 'Pesquisa inválida. Apenas letras, números e espaços são permitidos.'], 400);
        }

        $tipoPaletes = TipoPalete::where('tipo', 'like', '%' . $search . '%')
            ->orWhere('valor', 'like', '%' . $search . '%')
            ->with('user')
            ->get();
        if ($request->ajax()) {
        return response()->json($tipoPaletes);
        }

        return view('pages.admin.tipo-palete.tipo-palete', compact('tipoPaletes'));
    }
}
