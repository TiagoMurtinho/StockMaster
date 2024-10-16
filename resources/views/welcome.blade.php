@extends('layouts.app')

@section('content')

<div class="container">

    <section class="section login min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center justify-content-center">
                    <div>
                        <img class="logo-welcome" src="{{asset('assets/img/logo-stockmaster.png')}}" alt="StockMaster">
                    </div>
                    <div class="col-10">
                        <button class="btn btn-secondary w-100 mt-5" data-bs-toggle="modal" data-bs-target="#loginModal" >entrar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

    @include('auth.login')

@endsection
