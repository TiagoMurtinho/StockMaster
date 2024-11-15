@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('documento.documentos') }}</h5>
                    <input type="text" id="documentoSearch" class="form-control ms-3 searchQuerys" placeholder="Pesquisar por numero cliente ou tipo" style="width: 320px;">
                    <button type="button" class="btn btn-primary rounded-pill ms-auto" data-bs-toggle="modal"
                            data-bs-target="#modalAddDocumento">
                        {{__('documento.new')}}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="documentoTable" class="table table-hover table-transparent align-middle loader-table">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">{{ __('documento.numero') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.data') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.tipo_documento') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.cliente') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.user') }}</th>
                                <th scope="col" class="text-center">{{ __('documento.estado') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="documentoTableBody">
                            @foreach($documentos as $documento)

                                <tr class="clickable-row documentoRow" data-id="{{ $documento->id }}">
                                    <td class="align-middle text-center numero-cell">{{ $documento->numero }}</td>
                                    <td class="align-middle text-center data-cell">{{ $documento->data }}</td>
                                    <td class="align-middle text-center tipo-documento-cell">{{ $documento->tipo_documento->nome}}</td>
                                    <td class="align-middle text-center cliente-cell">{{ $documento->cliente->nome }}</td>
                                    <td class="align-middle text-center user-cell">{{ $documento->user->name }}</td>
                                    <td class="align-middle text-center estado-cell">{{ $documento->estado }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('documento.pdf', $documento->id) }}"
                                           class="btn btn-secondary btn-sm ms-2 no-click-propagation">
                                            {{__('documento.pdf')}}
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#deleteDocumentoModal{{ $documento->id }}">
                                            <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                                {{__('documento.delete')}}
                                            </button>
                                        </a>
                                    </td>
                                </tr>


                                @include('pages.admin.documento.modals.documento-delete-modal')
                            @endforeach

                            </tbody>
                        </table>
                        {{ $documentos->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('pages.admin.documento.modals.documento-modal')
    @include('pages.admin.documento.modals.add-documento-modal')
    @include('pages.admin.documento.modals.linha-documento-modal')

@endsection

