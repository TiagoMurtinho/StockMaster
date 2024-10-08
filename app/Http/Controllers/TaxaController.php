<?php

namespace App\Http\Controllers;

use App\Models\Taxa;
use App\Models\TipoPalete;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $taxas = Taxa::with('user')->paginate(10);
        return view('pages.admin.taxa.taxa', compact('taxas', 'users'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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

    public function search(Request $request)
    {
        $search = $request->input('query');

        if (empty($search)) {
            return redirect()->route('taxa.index');
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
