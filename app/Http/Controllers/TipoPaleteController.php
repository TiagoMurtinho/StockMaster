<?php

namespace App\Http\Controllers;

use App\Models\TipoPalete;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoPaleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $users = User::all();
        $tipoPaletes = TipoPalete::with('user')->get();
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
            'tipo' => 'required|string|max:45',
            'valor' => 'required|numeric',
            'user_id' => 'required|exists:user,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $tipoPalete = new TipoPalete();
        $tipoPalete->tipo = $request->input('tipo');
        $tipoPalete->valor = $request->input('valor');
        $tipoPalete->user_id = $request->input('user_id');
        $tipoPalete->save();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de palete criado com sucesso!',
            'redirect' => route('tipo-palete.index')
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
            'tipo' => 'required|string|max:45',
            'valor' => 'required|numeric',
            'user_id' => 'required|exists:user,id'
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
        $tipoPalete->user_id = $request->input('user_id');
        $tipoPalete->save();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de palete atualizado com sucesso!',
            'redirect' => route('tipo-palete.index')
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
}
