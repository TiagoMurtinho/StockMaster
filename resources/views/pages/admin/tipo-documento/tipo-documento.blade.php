@extends('layouts.app')

@section('content')

    <div class="container mt-5">
        <div class="actions-card">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5>{{ __('tipo-documento.tipo-documento') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-transparent align-middle">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">{{ __('tipo-documento.nome') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tipoDocumentos as $tipoDocumento)
                                <tr>
                                    <td class="align-middle text-center">{{ $tipoDocumento->nome }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

@endsection
