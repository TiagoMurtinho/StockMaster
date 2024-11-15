<!DOCTYPE html>
<html lang="pt">

<head>
    <title>{{ config('app.name') }}</title>

    @include('layouts.head')
</head>

<body>

@if(!Request::is('/') && !Request::is('welcome'))
    @if(!isset($navbar) || $navbar != false)
        <header id="header" class="header fixed-top d-flex align-items-center">
            @include('layouts.navbar')
        </header><!-- End Header -->
    @endif
    @if(!isset($sidebar) || $sidebar != false)
        <aside id="sidebar" class="sidebar">
            @include('layouts.sidebar')
        </aside>
    @endif
@endif

<main @if(!Request::is('/') && !Request::is('welcome')) id="main" @endif class="main @if(Request::is('/') || Request::is('welcome')) full-width @endif">
    <div class="mensagem-dinamica" style="display: none;"></div>
    @yield('content')
    @include('pages.admin.user.modals.user-change-password-modal')
</main>

<footer>
    @include('layouts.footer')
</footer>

</body>

</html>
