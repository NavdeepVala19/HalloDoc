{{-- HEADER SECTION --}}
<header class="header d-flex align-items-center justify-content-between px-3 border-bottom shadow ">
    <div class="d-flex align-items-center">
        <a href="" class="primary-empty menu-icon">
            <i class="bi bi-list"></i>
        </a>
        <a href=""><img class="logo img-fluid" src="{{ URL::asset('/assets/logo.png') }}" alt=""></a>
    </div>
    <div class="d-flex align-items-center gap-3">
        <button class="primary-empty toggle-mode" id="toggle-mode">
            <i class="bi bi-moon moon-icon"></i>
            <i class="bi bi-brightness-high sun-icon"></i>
        </button>
    </div>
</header>


<nav class="navbar-section shadow ">
    @yield('nav-links')
    {{-- <a href="">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <a href="">Providers</a>
    <a href="">Partners</a>
    <a href="">Access</a>
    <a href="">Records</a> --}}
</nav>