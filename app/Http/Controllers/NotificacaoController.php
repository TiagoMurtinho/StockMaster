<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{

    public function index(): JsonResponse
    {
        $userId = auth()->id();

        $notificacoes = Notificacao::whereHas('user', function($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('is_read', false);
        })
            ->with(['documento' => function($query) {
                $query->select('id', 'tipo_documento_id');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $notificacoes->map(function ($notificacao) {
            $notificacao->tipo_documento_id = $notificacao->documento->tipo_documento_id;
            return $notificacao;
        });

        return response()->json($notificacoes);
    }

    public function update(Request $request): JsonResponse
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

}
