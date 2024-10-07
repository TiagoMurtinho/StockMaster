@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('tipo-palete.tipo-palete') }}</h5>
                    <button type="button" class="btn btn-primary rounded-pill ms-auto" data-bs-toggle="modal"
                            data-bs-target="#addTipoPaleteModal">
                        {{__('tipo-palete.adicionar_tipo_palete')}}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tipoPaleteTable" class="table table-hover table-transparent align-middle">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">{{ __('tipo-palete.tipo') }}</th>
                                <th scope="col" class="text-center">{{ __('tipo-palete.valor') }}</th>
                                <th scope="col" class="text-center">{{ __('tipo-palete.user') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tipoPaletes as $tipoPalete)
                                <tr data-bs-toggle="modal" data-bs-target="#editTipoPaleteModal{{ $tipoPalete->id }}" class="tipoPaleteRow" data-id="{{ $tipoPalete->id }}">
                                    <td class="align-middle text-center">{{ $tipoPalete->tipo }}</td>
                                    <td class="align-middle text-center">{{ $tipoPalete->valor }}</td>
                                    <td class="align-middle text-center">{{ $tipoPalete->user->name }}</td>
                                    <td class="align-middle">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#deleteTipoPaleteModal{{ $tipoPalete->id }}">
                                            <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                                {{__('tipo-palete.delete')}}
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                @include('pages.admin.tipo-palete.modals.tipo-palete-delete-modal')

                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


    @include('pages.admin.tipo-palete.modals.tipo-palete-edit-modal')
    @include('pages.admin.tipo-palete.modals.tipo-palete-add-modal')

@endsection
