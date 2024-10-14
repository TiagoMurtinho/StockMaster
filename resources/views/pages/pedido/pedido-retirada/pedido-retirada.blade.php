@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('retirada.retirada') }}</h5>
                    <input type="text" id="retiradaSearch" class="form-control ms-3 searchQuerys" placeholder="Pesquisar por numero ou cliente" style="width: 280px;">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="retiradaTable" class="table table-hover table-transparent align-middle loader-table">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">{{ __('retirada.cliente') }}</th>
                                <th scope="col" class="text-center">{{ __('retirada.numero') }}</th>
                                <th scope="col" class="text-center">{{ __('retirada.data_retirada') }}</th>
                                <th scope="col" class="text-center">{{ __('retirada.quantidade') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($documentos as $documento)
                                <tr  data-bs-toggle="modal" data-bs-target="#retiradaModal{{$documento->id}}" class="retiradaRow" data-documento-id="{{ $documento->id }}">
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
                                        {{ $totalQuantidade }} {{ __('retirada.paletes') }}
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

    @include('pages.pedido.pedido-retirada.modals.retirada-modal')

@endsection
