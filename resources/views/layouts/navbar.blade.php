<nav class="navbar-custom">
    <div class="navbar-content">
        <div class="navbar-left">
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="bi bi-list"></i> {{-- Ganti fa-solid fa-bars -> bi bi-list --}}
            </button>
            <h4 class="page-title">@yield('page-title', 'Dashboard')</h4>
        </div>

        <div class="navbar-right">
            <div class="nav-icon">
                <i class="bi bi-bell"></i> {{-- Ganti fa-regular fa-bell -> bi bi-bell --}}
                <span class="badge-notification">{{ $notifications ?? 5 }}</span>
            </div>

            <div class="dark-mode-toggle" id="darkModeToggle">
                <i class="bi bi-moon"></i> {{-- Ganti fa-regular fa-moon -> bi bi-moon --}}
            </div>

            <div class="user-profile" id="userProfile">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin+WG') }}&background=16a34a&color=fff" alt="User">
                <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Admin WG' }}</span>
                <i class="bi bi-chevron-down ms-1 d-none d-md-inline"></i> {{-- Ganti fa-solid fa-chevron-down -> bi bi-chevron-down --}}

                <div class="dropdown-menu-custom">
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-gear"></i> {{-- Ganti fa-regular fa-gear -> bi bi-gear --}}
                        <span>Pengaturan</span>
                    </a>
                    <div class="dropdown-divider"></div>

                    {{-- Form Logout --}}
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: none;">
                        @csrf
                    </form>

                    <a href="#" class="dropdown-item" style="color: #ef4444;" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                        <i class="bi bi-box-arrow-right"></i> {{-- Ganti fa-solid fa-arrow-right-from-bracket -> bi bi-box-arrow-right --}}
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
