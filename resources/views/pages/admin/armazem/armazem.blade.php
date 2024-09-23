@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('armazem.armazem') }}</h5>
                    <button type="button" class="btn btn-primary rounded-pill ms-auto" data-bs-toggle="modal"
                            data-bs-target="#addArmazemModal">
                        {{__('armazem.novo_armazem')}}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-transparent align-middle">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">{{ __('armazem.nome') }}</th>
                                <th scope="col" class="text-center">{{ __('armazem.capacidade') }}</th>
                                <th scope="col" class="text-center">{{ __('armazem.tipo_palete') }}</th>
                                <th scope="col" class="text-center">{{ __('armazem.user') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($armazens as $armazem)
                                <tr data-bs-toggle="modal" data-bs-target="#editArmazemModal{{ $armazem->id }}">
                                    <td class="align-middle text-center">{{ $armazem->nome }}</td>
                                    <td class="align-middle text-center">{{ $armazem->capacidade }}</td>
                                    <td class="align-middle text-center">{{ $armazem->tipo_palete->tipo }}</td>
                                    <td class="align-middle text-center">{{ $armazem->user->nome }}</td>
                                    <td class="align-middle">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#deleteArmazemModal{{ $armazem->id }}" onclick="confirmDelete('deleteArmazemForm{{ $armazem->id }}', '{{ route('armazem.destroy', $armazem->id) }}')">
                                            <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                                {{__('armazem.delete')}}
                                            </button>
                                        </a>
                                    </td>
                                </tr>

                                @include('pages.admin.armazem.modals.armazem-delete-modal')

                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    @include('pages.admin.armazem.modals.armazem-add-modal')
    @include('pages.admin.armazem.modals.armazem-edit-modal')

@endsection
