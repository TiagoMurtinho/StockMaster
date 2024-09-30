<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use App\Models\NotificacaoUser;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();

        $notificacoes = Notificacao::whereHas('user', function($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('is_read', false);
        })->orderBy('created_at', 'desc')->get();

        return response()->json($notificacoes);
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
        //
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
    public function update(Request $request)
    {
        $userId = auth()->id();

        $notificacoes = Notificacao::whereHas('user', function($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('is_read', false);
        })->get();

        foreach ($notificacoes as $notificacao) {
            $notificacao->user()->updateExistingPivot($userId, ['is_read' => true]);
        }

        return response()->json(['message' => 'Notificações marcadas como lidas']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
