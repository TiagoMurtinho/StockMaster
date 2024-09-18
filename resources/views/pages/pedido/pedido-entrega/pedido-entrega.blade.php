@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('entrega.entrega') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-transparent align-middle">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">{{ __('entrega.cliente') }}</th>
                                <th scope="col" class="text-center">{{ __('entrega.numero') }}</th>
                                <th scope="col" class="text-center">{{ __('entrega.data_entrega') }}</th>
                                <th scope="col" class="text-center">{{ __('entrega.quantidade') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($documentos as $documento)
                                <tr>
                                    <td class="align-middle text-center">{{ $documento->cliente->nome }}</td>
                                    <td class="align-middle text-center">{{ $documento->numero }}</td>
                                    <td class="align-middle text-center">
                                        @foreach($documento->linha_documento as $linha)
                                            {{ $linha->previsao }}
                                        @endforeach
                                    </td>
                                    <td class="align-middle text-center">
                                        @php
                                            $totalQuantidade = 0;
                                            foreach ($documento->linha_documento as $linha) {
                                                foreach ($linha->tipo_palete as $tipoPalete) {
                                                    $totalQuantidade += $tipoPalete->pivot->quantidade;
                                                }
                                            }
                                        @endphp
                                        {{ $totalQuantidade }} {{__('entrega.paletes')}}
                                    </td>
                                    <td class="align-middle">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#rececaoModal{{ $linha->id }}">
                                            <i class="bi bi-arrow-right-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('pages.pedido.pedido-entrega.modals.rececao-modal')

@endsection
