@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('entrega.entregas') }}</h5>
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
                                <th scope="col" class="text-center">{{ __('entrega.tipo_palete') }}</th>
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
                                            {{ $linha->data_entrega }}
                                        @endforeach
                                    </td>
                                    <td class="align-middle text-center">
                                        @foreach($documento->linha_documento as $linha)
                                                {{ $linha->quantidade }}
                                        @endforeach
                                    </td>
                                    <td class="align-middle text-center">
                                        @foreach($documento->linha_documento as $linha)
                                                {{ $linha->tipo_palete->tipo }}
                                        @endforeach
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

@endsection
