@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('entrega.entrega') }}</h5>
                    <input type="text" id="entregaSearch" class="form-control ms-3" placeholder="Pesquisar por numero ou cliente" style="width: 280px;">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="entregaTable" class="table table-hover table-transparent align-middle">
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
                                <tr data-bs-toggle="modal" data-bs-target="#rececaoModal" class="entregaRow" data-id="{{ $documento->id }}" data-cliente-id="{{ $documento->cliente_id }}" data-documento-numero="{{ $documento->numero }}">
                                    <td class="align-middle text-center">{{ $documento->cliente->nome }}</td>
                                    <td class="align-middle text-center">{{ $documento->numero }}</td>
                                    <td class="align-middle text-center">

                                            {{ $documento->previsao }}

                                    </td>
                                    <td class="align-middle text-center">
                                        @php
                                            $totalQuantidade = 0;
                                            foreach ($documento->tipo_palete as $tipoPalete) {
                                                $totalQuantidade += $tipoPalete->pivot->quantidade;
                                            }
                                        @endphp
                                        {{ $totalQuantidade }} {{__('entrega.paletes')}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $documentos->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('pages.pedido.pedido-entrega.modals.rececao-modal')

@endsection
