@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('artigo.artigo') }}</h5>
                    <input type="text" id="artigoSearch" class="form-control ms-3" placeholder="Pesquisar por nome, referência ou cliente" style="width: 340px;">
                    <button type="button" class="btn btn-primary rounded-pill ms-auto" data-bs-toggle="modal"
                            data-bs-target="#addArtigoModal">
                        {{__('artigo.novo_artigo')}}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="artigoTable" class="table table-hover table-transparent align-middle loader-table">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">{{ __('artigo.nome') }}</th>
                                <th scope="col" class="text-center">{{ __('artigo.referencia') }}</th>
                                <th scope="col" class="text-center">{{ __('artigo.cliente') }}</th>
                                <th scope="col" class="text-center">{{ __('artigo.user') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($artigos as $artigo)
                                <tr data-bs-toggle="modal" data-bs-target="#editArtigoModal{{ $artigo->id }}" class="artigoRow" data-id="{{ $artigo->id }}">
                                    <td class="align-middle text-center">{{ $artigo->nome }}</td>
                                    <td class="align-middle text-center">{{ $artigo->referencia }}</td>
                                    <td class="align-middle text-center">{{ $artigo->cliente->nome }}</td>
                                    <td class="align-middle text-center">{{ $artigo->user->name }}</td>
                                    <td class="align-middle">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#deleteArtigoModal{{ $artigo->id }}">
                                            <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                                {{__('artigo.delete')}}
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                @include('pages.admin.artigo.modals.artigo-delete-modal')
                            @endforeach
                            </tbody>
                        </table>
                        {{ $artigos->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    @include('pages.admin.artigo.modals.artigo-edit-modal')
    @include('pages.admin.artigo.modals.artigo-add-modal')


@endsection
