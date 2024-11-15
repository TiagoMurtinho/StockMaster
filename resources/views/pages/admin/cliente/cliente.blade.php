@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('cliente.cliente') }}</h5>
                    <input type="text" id="clienteSearch" class="form-control ms-3 searchQuerys" placeholder="Pesquisar por nome ou NIF" style="width: 300px;">
                    <button type="button" class="btn btn-primary rounded-pill ms-auto" data-bs-toggle="modal"
                            data-bs-target="#addClienteModal">
                        {{__('cliente.novo_cliente')}}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="clienteTable" class="table table-hover table-transparent align-middle loader-table">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">{{ __('cliente.nome') }}</th>
                                <th scope="col" class="text-center">{{ __('cliente.morada') }}</th>
                                <th scope="col" class="text-center">{{ __('cliente.codigo_postal') }}</th>
                                <th scope="col" class="text-center">{{ __('cliente.nif') }}</th>
                                <th scope="col" class="text-center">{{ __('cliente.user') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clientes as $cliente)
                                <tr data-bs-toggle="modal" data-bs-target="#editClienteModal{{ $cliente->id }}" class="clienteRow" data-id="{{ $cliente->id }}">
                                    <td class="align-middle text-center">{{ $cliente->nome }}</td>
                                    <td class="align-middle text-center">{{ $cliente->morada }}</td>
                                    <td class="align-middle text-center">{{ $cliente->codigo_postal }}</td>
                                    <td class="align-middle text-center">{{ $cliente->nif }}</td>
                                    <td class="align-middle text-center">{{ $cliente->user->name }}</td>
                                    <td class="align-middle">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#deleteClienteModal{{ $cliente->id }}">
                                            <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                                Eliminar
                                            </button>
                                        </a>
                                    </td>
                                </tr>

                                @include('pages.admin.cliente.modals.cliente-delete-modal')

                            @endforeach
                            </tbody>
                        </table>
                        {{ $clientes->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    @include('pages.admin.cliente.modals.cliente-add-modal')
    @include('pages.admin.cliente.modals.cliente-edit-modal')

@endsection
