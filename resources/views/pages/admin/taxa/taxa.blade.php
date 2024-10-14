@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('taxa.taxa') }}</h5>
                    <input type="text" id="taxaSearch" class="form-control ms-3" placeholder="Pesquisar por nome" style="width: 200px;">
                    <button type="button" class="btn btn-primary rounded-pill ms-auto" data-bs-toggle="modal"
                            data-bs-target="#addTaxaModal">
                        {{__('taxa.nova_taxa')}}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="taxaTable" class="table table-hover table-transparent align-middle loader-table">
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
                                <tr data-bs-toggle="modal" data-bs-target="#editTaxaModal{{ $taxa->id }}" class="taxaRow" data-id="{{ $taxa->id }}">
                                    <td class="align-middle text-center">{{ $taxa->nome }}</td>
                                    <td class="align-middle text-center">{{ $taxa->valor }}</td>
                                    <td class="align-middle text-center">{{ $taxa->user->name }}</td>
                                    <td class="align-middle">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#deleteTaxaModal{{ $taxa->id }}">
                                            <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                                {{__('taxa.delete')}}
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                @include('pages.admin.taxa.modals.taxa-delete-modal')
                            @endforeach
                            </tbody>
                        </table>
                        {{ $taxas->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>


    @include('pages.admin.taxa.modals.taxa-edit-modal')
    @include('pages.admin.taxa.modals.taxa-add-modal')

@endsection
