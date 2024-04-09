{{-- HEADER SECTION --}}
<header class="header d-flex align-items-center justify-content-between px-3 border-bottom shadow">
    <div class="d-flex align-items-center">
        <button class="primary-empty menu-icon">
            <i class="bi bi-list"></i>
        </button>
        <a href=""><img class="logo img-fluid" src="{{ URL::asset('/assets/logo.png') }}"
                alt="HalloDoc Project Logo"></a>
    </div>
    <div class="d-flex align-items-center  gap-3">
        <span class="welcome-msg align-self-center">welcome,
            {{-- Admin UserName or Provider UserName will be fetched and showed here --}}
            <strong> {{ Auth::user()->username }} </strong>
        </span>
        <a href="{{ route('logout') }}" class="logout-btn primary-empty">
            <i class="bi bi-box-arrow-right"></i>
        </a>
        <a href="{{ route('logout') }}" class="logout-link primary-empty">Logout</a>
        <button class="primary-empty toggle-mode" id="toggle-mode">
            <i class="bi bi-moon"></i>
        </button>
    </div>
</header>

<nav class="navbar-section shadow">
    @yield('nav-links')
</nav>
