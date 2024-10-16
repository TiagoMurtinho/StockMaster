<?php

namespace App\Http\Controllers;

use App\Models\Armazem;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\TipoDocumento;
use App\Models\TipoPalete;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PedidoEntregaController extends Controller
{
    public function index(): Factory|View|Application
    {

        $documentos = Documento::with('tipo_palete')
            ->where('tipo_documento_id', 1)
            ->where('estado', 'pendente')
            ->orderBy('documento.previsao', 'asc')
            ->paginate(10);

        $armazens = Armazem::all();
        $tiposDocumento = TipoDocumento::all();
        $clientes = Cliente::all();
        $tipoPaletes = TipoPalete::all();

        return view('pages.pedido.pedido-entrega.pedido-entrega', compact('documentos','tiposDocumento', 'clientes', 'tipoPaletes', 'armazens'));
    }

    public function search(Request $request): Application|Factory|View|JsonResponse|RedirectResponse
    {
        $search = $request->input('query');

        if (empty($search)) {
            return redirect()->route('pedido-entrega.index');
        }

        if (!preg_match('/^[a-zA-Z0-9\s]*$/', $search)) {
            return response()->json(['error' => 'Pesquisa inválida. Apenas letras, números e espaços são permitidos.'], 400);
        }

        $documentos = Documento::where('tipo_documento_id', 1)
            ->where('estado', 'pendente')
        ->where(function($query) use ($search) {
            $query->whereHas('cliente', function($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%');
            })
                ->orWhere('numero', 'like', '%' . $search . '%');
        })
            ->with('cliente', 'tipo_palete')
            ->get();

        if ($request->ajax()) {
            return response()->json($documentos);
        }

        return view('pages.pedido.pedido-entrega.pedido-entrega', compact('documentos'));
    }
}
