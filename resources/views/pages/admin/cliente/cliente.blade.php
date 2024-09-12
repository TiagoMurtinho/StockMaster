@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('cliente.cliente') }}</h5>
                    <a type="button" class="align-items-center ms-2" data-bs-toggle="modal" data-bs-target="#addClienteModal">
                        <i class="ri-add-circle-line plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-transparent align-middle">
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
                                <tr>
                                    <td class="align-middle text-center">{{ $cliente->nome }}</td>
                                    <td class="align-middle text-center">{{ $cliente->morada }}</td>
                                    <td class="align-middle text-center">{{ $cliente->codigo_postal }}</td>
                                    <td class="align-middle text-center">{{ $cliente->nif }}</td>
                                    <td class="align-middle text-center">{{ $cliente->user->nome }}</td>
                                    <td class="align-middle">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editClienteModal{{ $cliente->id }}">
                                            <i class="bi bi-pencil-square me-2"></i>
                                        </a>
                                        {{--<a href="#" data-bs-toggle="modal" data-bs-target="#deleteClienteModal{{ $tipoPalete->id }}" onclick="confirmDelete('deleteTipoPaleteForm{{ $tipoPalete->id }}', '{{ route('tipo-palete.destroy', $tipoPalete->id) }}')">--}}
                                            <i class="bi bi-trash"></i>
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

    @include('pages.admin.cliente.modals.cliente-add-modal')
    @include('pages.admin.cliente.modals.cliente-edit-modal')

@endsection
