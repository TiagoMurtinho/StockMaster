<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\Cliente;
use App\Models\District;
use App\Models\TipoPalete;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArmazemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $tipoPaletes = TipoPalete::all();
        $users = User::all();
        $armazens = Armazem::with('user', 'tipo_palete')->get();
        return view('pages.admin.armazem.armazem', compact('armazens', 'users', 'tipoPaletes'));
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
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:45',
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

        return response()->json([
            'success' => true,
            'message' => 'Armazem criado com sucesso!',
            'redirect' => route('armazem.index')
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
