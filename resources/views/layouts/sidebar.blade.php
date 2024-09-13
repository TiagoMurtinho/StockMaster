<ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
        <a class="nav-link collapsed" href="#">
            <i class="bi bi-grid"></i>
            <span>Home</span>
        </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#admin-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Administração</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="admin-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{route('tipo-palete.index')}}">
                    <i class="bi bi-circle"></i><span>{{__('sidebar.tipo_paletes')}}</span>
                </a>
            </li>
            <li>
                <a href="{{route('cliente.index')}}">
                    <i class="bi bi-circle"></i><span>{{__('sidebar.clientes')}}</span>
                </a>
            </li>
            <li>
                <a href="{{route('armazem.index')}}">
                    <i class="bi bi-circle"></i><span>{{__('sidebar.armazens')}}</span>
                </a>
            </li>
            <li>
                <a href="{{route('artigo.index')}}">
                    <i class="bi bi-circle"></i><span>{{__('sidebar.artigos')}}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('documento.index') }}">
                    <i class="bi bi-circle"></i><span>{{ __('sidebar.documentos') }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('tipo-documento.index')}}">
                    <i class="bi bi-circle"></i><span>{{__('sidebar.tipo_documentos')}}</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#pedidos-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Pedidos</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="pedidos-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{route('pedido-entrega.index')}}">
                    <i class="bi bi-circle"></i><span>{{__('sidebar.pedidos_de_entrega')}}</span>
                </a>
            </li>
            <li>
                <a href="{{--{{route('tipo-documento.index')}}--}}">
                    <i class="bi bi-circle"></i><span>{{__('sidebar.tipo_documentos')}}</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="pages-login.html">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Login</span>
        </a>
    </li><!-- End Login Page Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" href="pages-error-404.html">
            <i class="bi bi-dash-circle"></i>
            <span>Error 404</span>
        </a>
    </li><!-- End Error 404 Page Nav -->

    <li class="nav-item">
        <a class="nav-link " href="pages-blank.html">
            <i class="bi bi-file-earmark"></i>
            <span>Blank</span>
        </a>
    </li><!-- End Blank Page Nav -->

</ul>
