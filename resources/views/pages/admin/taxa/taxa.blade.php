@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('taxa.taxa') }}</h5>
                    <a type="button" class="align-items-center ms-2" data-bs-toggle="modal" data-bs-target="#addTaxaModal">
                        <i class="ri-add-circle-line plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-transparent align-middle">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">{{ __('taxa.nome') }}</th>
                                <th scope="col" class="text-center">{{ __('taxa.valor') }}</th>
                                <th scope="col" class="text-center">{{ __('taxa.user') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($taxas as $taxa)
                                <tr>
                                    <td class="align-middle text-center">{{ $taxa->nome }}</td>
                                    <td class="align-middle text-center">{{ $taxa->valor }}</td>
                                    <td class="align-middle text-center">{{ $taxa->user->nome }}</td>
                                    <td class="align-middle">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editTaxaModal{{ $taxa->id }}">
                                            <i class="bi bi-pencil-square me-2"></i>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#deleteTaxaModal{{ $taxa->id }}" onclick="confirmDelete('deleteTaxaForm{{ $taxa->id }}', '{{ route('taxa.destroy', $taxa->id) }}')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @include('pages.admin.taxa.modals.taxa-delete-modal')
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


    @include('pages.admin.taxa.modals.taxa-edit-modal')
    @include('pages.admin.taxa.modals.taxa-add-modal')

@endsection
