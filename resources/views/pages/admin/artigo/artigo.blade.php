@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 ms-2">{{ __('artigo.artigo') }}</h5>
                    <a type="button" class="align-items-center ms-2" data-bs-toggle="modal" data-bs-target="#addArmazemModal">
                    <i class="ri-add-circle-line plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-transparent align-middle">
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
                                <tr>
                                    <td class="align-middle text-center">{{ $artigo->nome }}</td>
                                    <td class="align-middle text-center">{{ $artigo->referencia }}</td>
                                    <td class="align-middle text-center">{{ $artigo->cliente->nome }}</td>
                                    <td class="align-middle text-center">{{ $artigo->user->nome }}</td>
                                    <td class="align-middle">
                                        {{--<a href="#" data-bs-toggle="modal" data-bs-target="#editArmazemModal{{ $artigo->id }}">--}}
                                            <i class="bi bi-pencil-square me-2"></i>
                                        </a>
                                        {{--<a href="#" data-bs-toggle="modal" data-bs-target="#deleteArmazemModal{{ $artigo->id }}" onclick="confirmDelete('deleteArmazemForm{{ $artigo->id }}', '{{ route('artigo.destroy', $artigo->id) }}')">--}}
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

@endsection
