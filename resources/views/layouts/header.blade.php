{{-- HEADER SECTION --}}
<header class="header d-flex align-items-center justify-content-between px-3 border-bottom shadow bg-body-tertiary">
    <div class="d-flex align-items-center">
        <a href="" class="primary-empty menu-icon">
            <i class="bi bi-list"></i>
        </a>
        <a href=""><img class="logo img-fluid"  src="{{ URL::asset('/assets/logo.png') }}" alt=""></a>
    </div>
    <div class="d-flex align-items-center  gap-3">
        <span class="welcome-msg align-self-center">welcome
            {{-- Admin Names will be fetched and showed here --}}
            {{-- <strong> {{ admin . firstname }} {{ admin . lastname }}</strong>  --}}
        </span>
        <a href="" class="logout-btn primary-empty">
            <i class="bi bi-box-arrow-right"></i>
        </a>
<<<<<<< HEAD
        <a href="" class="logout-link primary-empty">Logout</a>
        <a href="" class="primary-empty toggle-mode">
=======
        <a href="{{route('logout')}}" class="logout-link primary-empty">Logout</a>
        <button class="primary-empty toggle-mode" id="toggle-mode">
>>>>>>> a73b8fa7a89b82dbd3aaa37ebfc5c5472a24b2a2
            <i class="bi bi-moon"></i>
        </button>
    </div>
</header>

<nav class="navbar-section shadow bg-body-tertiary ">
    @yield('nav-links')
    {{-- <a href="">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <a href="">Providers</a>
    <a href="">Partners</a>
    <a href="">Access</a>
    <a href="">Records</a> --}}
</nav>
