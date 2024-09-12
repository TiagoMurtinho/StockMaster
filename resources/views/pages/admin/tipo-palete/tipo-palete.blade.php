@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('tipo-palete.tipo-palete') }}</h5>
                    <a type="button" class="align-items-center ms-2" data-bs-toggle="modal" data-bs-target="#addTipoPaleteModal">
                        <i class="ri-add-circle-line plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-transparent align-middle">
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
                                <tr>
                                    <td class="align-middle text-center">{{ $tipoPalete->tipo }}</td>
                                    <td class="align-middle text-center">{{ $tipoPalete->valor }}</td>
                                    <td class="align-middle text-center">{{ $tipoPalete->user->nome }}</td>
                                    <td class="align-middle">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editTipoPaleteModal{{ $tipoPalete->id }}">
                                            <i class="bi bi-pencil-square me-2"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#deleteTipoPaleteModal{{ $tipoPalete->id }}" onclick="confirmDelete('deleteTipoPaleteForm{{ $tipoPalete->id }}', '{{ route('tipo-palete.destroy', $tipoPalete->id) }}')">
                                            <i class="bi bi-trash"></i>
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
