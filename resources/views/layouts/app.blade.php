<!DOCTYPE html>
<html lang="en">

<head>
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
</main>

<footer>
    @include('layouts.footer')
</footer>

</body>

</html>
