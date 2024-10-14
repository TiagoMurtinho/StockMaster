<div class="d-flex align-items-center justify-content-between">
    <a href="#" class="logo d-flex align-items-start">
        <img src="{{asset('assets/img/logo-stockmaster.png')}}" alt="StockMaster">
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
</div>

<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

        <li class="nav-item dropdown">

            <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                <span class="badge bg-primary badge-number"></span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" id="notificationDropdown">
                <li class="dropdown-header">
                    {{__('navbar.have')}} <span id="notificationCount">0</span> {{__('navbar.new_notifications')}}
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <div id="notificationItems" class="text-center"></div>
            </ul>

        </li>

        <li class="nav-item dropdown">
            <a class="nav-link nav-icon" href="{{ url('/chatify') }}">
                <i class="bi bi-chat-left-text"></i>
                <span id="unseen-counter" class="badge bg-danger badge-number"></span>
            </a>
        </li>

        <li class="nav-item dropdown pe-3">

            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-md-block dropdown-toggle ps-2">{{Auth::user()->name}}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                <li>
                    <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="bi bi-gear"></i>
                        <span>{{__('navbar.change_password')}}</span>
                    </a>
                </li>

                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a class="dropdown-item d-flex align-items-center" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>{{__('navbar.sign_out')}}</span>
                    </a>
                </li>

            </ul>
        </li>
    </ul>
</nav>
