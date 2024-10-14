<?php

namespace App\Http\Controllers;

use App\Models\TipoPalete;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TipoPaleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $users = User::all();
        $tipoPaletes = TipoPalete::with('user')->paginate(10);
        return view('pages.admin.tipo-palete.tipo-palete', compact('tipoPaletes', 'users'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
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

    public function search(Request $request)
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
