@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('user.user') }}</h5>
                    <button type="button" class="btn btn-primary rounded-pill ms-auto" data-bs-toggle="modal"
                            data-bs-target="#addUserModal">
                        {{__('user.novo_user')}}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-transparent align-middle">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">{{ __('user.nome') }}</th>
                                <th scope="col" class="text-center">{{ __('user.email') }}</th>
                                <th scope="col" class="text-center">{{ __('user.contacto') }}</th>
                                <th scope="col" class="text-center">{{ __('user.salario') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr {{--data-bs-toggle="modal" data-bs-target="#editTaxaModal{{ $user->id }}"--}}>
                                    <td class="align-middle text-center">{{ $user->nome }}</td>
                                    <td class="align-middle text-center">{{ $user->email }}</td>
                                    <td class="align-middle text-center">{{ $user->contacto }}</td>
                                    <td class="align-middle text-center">{{ $user->salario }}</td>
                                    <td class="align-middle">
                                       {{-- <a href="#" data-bs-toggle="modal" data-bs-target="#deleteTaxaModal{{ $taxa->id }}" onclick="confirmDelete('deleteTaxaForm{{ $taxa->id }}', '{{ route('taxa.destroy', $taxa->id) }}')">--}}
                                            <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                                {{__('user.delete')}}
                                            </button>
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

    @include('pages.admin.user.modals.user-add-modal')

@endsection
