<div class="d-flex align-items-center justify-content-between">
    <a href="#" class="logo d-flex align-items-start">
        <img src="assets/img/logo-stockmaster.png" alt="StockMaster">
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->


<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

        <li class="nav-item dropdown">

            <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                <span class="badge bg-primary badge-number"></span>
            </a><!-- End Notification Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" id="notificationDropdown">
                <li class="dropdown-header">
                    You have <span id="notificationCount">0</span> new notifications
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <div id="notificationItems" class="text-center"></div>
            </ul>

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">
            <a class="nav-link nav-icon" href="{{ url('/chatify') }}" data-bs-toggle="dropdown">
                <i class="bi bi-chat-left-text"></i>
                <span id="unseen-counter" class="badge bg-danger badge-number"></span>
            </a>
        </li>

        <li class="nav-item dropdown pe-3">

            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-md-block dropdown-toggle ps-2">{{Auth::user()->name}}</span>
            </a><!-- End Profile Iamge Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                <li>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <i class="bi bi-gear"></i>
                        <span>Account Settings</span>
                    </a>
                </li>

                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a class="dropdown-item d-flex align-items-center" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Sign Out</span>
                    </a>
                </li>

            </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

    </ul>
</nav><!-- End Icons Navigation -->
