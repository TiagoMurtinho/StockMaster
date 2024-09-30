@extends('layouts.app')

@section('content')

    <div class="container">

        <section class="section dashboard">
            <div class="row">
            <div class="col-12">
                @if (session('status'))
                    <div class="alert alert-success mensagem-dinamica" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
        </section>

        <section class="section login d-flex flex-column align-items-center justify-content-center home-logo">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center justify-content-center">
                        <div>
                            <img class="logo-welcome" src="{{asset('assets/img/logo-stockmaster.png')}}" alt="StockMaster">
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

@endsection
