<nav class="navbar-custom">
    <div class="navbar-content">
        <div class="navbar-left">
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h4 class="page-title">@yield('page-title', 'Dashboard')</h4>
        </div>

        <div class="navbar-right">
            <div class="nav-icon">
                <i class="fa-regular fa-bell"></i>
                <span class="badge-notification">{{ $notifications ?? 5 }}</span>
            </div>

            <div class="dark-mode-toggle" id="darkModeToggle">
                <i class="fa-regular fa-moon"></i>
            </div>

            <div class="user-profile" id="userProfile">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin+WG') }}&background=16a34a&color=fff" alt="User">
                <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Admin WG' }}</span>
                <i class="fa-solid fa-chevron-down ms-1 d-none d-md-inline"></i>

                <div class="dropdown-menu-custom">
                    <a href="#" class="dropdown-item">
                        <i class="fa-regular fa-user"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fa-regular fa-gear"></i>
                        <span>Pengaturan</span>
                    </a>
                    <div class="dropdown-divider"></div>

                    {{-- Form Logout --}}
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: none;">
                        @csrf
                    </form>

                    <a href="#" class="dropdown-item" style="color: #ef4444;" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
