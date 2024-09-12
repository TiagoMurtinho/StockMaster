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
                                            <i class="ph ph-pencil-simple edit-pencil me-1"></i>
                                        </a>
                                        {{--<a href="#" data-bs-toggle="modal" data-bs-target="#deleteRegionModal{{ $region->id }}" onclick="confirmDelete('deleteRegionForm{{ $region->id }}','{{ route('regions.destroy', $region->id) }}')">--}}
                                            <i class="ph ph-trash delete-trash me-1"></i>
                                        {{--</a>--}}
                                    </td>
                                </tr>

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
