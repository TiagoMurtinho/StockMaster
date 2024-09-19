@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('documento.documentos') }}</h5>
                    <button type="button" class="btn btn-primary rounded-pill ms-auto" data-bs-toggle="modal"
                            data-bs-target="#modalAddDocumento">
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
                                <th scope="col" class="text-center">{{ __('documento.tipo_documento') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.cliente') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.user') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($documentos as $documento)
                                <tr class="clickable-row" data-id="{{ $documento->id }}">
                                    <td class="align-middle text-center">{{ $documento->numero }}</td>
                                    <td class="align-middle text-center">{{ $documento->data }}</td>
                                    <td class="align-middle text-center">{{ $documento->tipo_documento->nome}}</td>
                                    <td class="align-middle text-center">{{ $documento->cliente->nome }}</td>
                                    <td class="align-middle text-center">{{ $documento->user->nome }}</td>
                                    <td class="text-center"> <!-- Célula para o botão -->
                                        <a href="{{ route('documento.pdf', $documento->id) }}"
                                           class="btn btn-secondary btn-sm">
                                            Gerar PDF
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

    @include('pages.admin.documento.modals.add-documento-modal')
    @include('pages.admin.documento.modals.linha-documento-modal')
    @include('pages.admin.documento.modals.documento-modal')
@endsection

