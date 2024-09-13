@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('documento.documentos') }}</h5>
                    <button type="button" class="btn btn-primary rounded-pill ms-auto" data-bs-toggle="modal"
                            data-bs-target="#tipoDocumentoModal">
                        Novo Documento
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-transparent align-middle">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">{{ __('documento.numero') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.data') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.matricula') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.morada') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.hora_carga') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.descarga') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.total') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.tipo_documento') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.cliente') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.user') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($documentos as $documento)
                                <tr>
                                    <td class="align-middle text-center">{{ $documento->numero }}</td>
                                    <td class="align-middle text-center">{{ $documento->data }}</td>
                                    <td class="align-middle text-center">{{ $documento->matricula }}</td>
                                    <td class="align-middle text-center">{{ $documento->morada }}</td>
                                    <td class="align-middle text-center">{{ $documento->hora_carga }}</td>
                                    <td class="align-middle text-center">{{ $documento->descarga }}</td>
                                    <td class="align-middle text-center">{{ $documento->total }}</td>
                                    <td class="align-middle text-center">{{ $documento->tipo_documento->nome}}</td>
                                    <td class="align-middle text-center">{{ $documento->cliente->nome }}</td>
                                    <td class="align-middle text-center">{{ $documento->user->nome }}</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('pages.documento.modals.tipo-documento-modal')
    @include('pages.documento.modals.documento-modal')
    @include('pages.documento.modals.linha-documento-modal')
@endsection

