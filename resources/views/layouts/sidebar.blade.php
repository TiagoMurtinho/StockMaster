<ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->routeIs('home') ? 'active' : '' }}" href="{{route('home')}}">
            <i class="bi bi-grid {{ request()->routeIs('home') ? 'active-icon' : '' }}"></i>
            <span>Home</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#admin-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Administração</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="admin-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a class="{{ request()->routeIs('tipo-palete.index') ? 'active' : '' }}" href="{{route('tipo-palete.index')}}" data-ajax="true">
                    <i class="bi bi-circle {{ request()->routeIs('tipo-palete.index') ? 'active-icon' : '' }}"></i><span>{{__('sidebar.tipo_paletes')}}</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('cliente.index') ? 'active' : '' }}" href="{{route('cliente.index')}}" data-ajax="true">
                    <i class="bi bi-circle {{ request()->routeIs('cliente.index') ? 'active-icon' : '' }}"></i><span>{{__('sidebar.clientes')}}</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('armazem.index') ? 'active' : '' }}" href="{{route('armazem.index')}}" data-ajax="true">
                    <i class="bi bi-circle {{ request()->routeIs('armazem.index') ? 'active-icon' : '' }}"></i><span>{{__('sidebar.armazens')}}</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('artigo.index') ? 'active' : '' }}" href="{{route('artigo.index')}}" data-ajax="true">
                    <i class="bi bi-circle {{ request()->routeIs('artigo.index') ? 'active-icon' : '' }}"></i><span>{{__('sidebar.artigos')}}</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('taxa.index') ? 'active' : '' }}" href="{{route('taxa.index')}}" data-ajax="true">
                    <i class="bi bi-circle {{ request()->routeIs('taxa.index') ? 'active-icon' : '' }}"></i><span>{{__('sidebar.taxas')}}</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('documento.index') ? 'active' : '' }}" href="{{ route('documento.index') }}" data-ajax="true">
                    <i class="bi bi-circle {{ request()->routeIs('documento.index') ? 'active-icon' : '' }}"></i><span>{{ __('sidebar.documentos') }}</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#pedidos-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Pedidos</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="pedidos-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a class="{{ request()->routeIs('pedido-entrega.index') ? 'active' : '' }}" href="{{route('pedido-entrega.index')}}" data-ajax="true">
                    <i class="bi bi-circle {{ request()->routeIs('pedido-entrega.index') ? 'active-icon' : '' }}"></i><span>{{__('sidebar.pedidos_de_entrega')}}</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('pedido-retirada.index') ? 'active' : '' }}" href="{{route('pedido-retirada.index')}}" data-ajax="true">
                    <i class="bi bi-circle {{ request()->routeIs('pedido-retirada.index') ? 'active-icon' : '' }}"></i><span>{{__('sidebar.pedidos_de_retirada')}}</span>
                </a>
            </li>
        </ul>
    </li>

</ul>
