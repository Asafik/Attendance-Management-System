<nav class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <i class="fa-solid fa-building"></i>
        <div class="logo-text">
             <span>{{ $company->name ?? '-' }}</span>
            <small>Attendance System</small>
        </div>
    </div>

    <div class="sidebar-menu">
        <!-- MAIN GROUP -->
        <div class="menu-group">
            <div class="menu-group-title">MAIN</div>
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="menu-icon fa-solid fa-chart-pie"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </div>
        </div>

        <!-- DATA GROUP -->
        <div class="menu-group">
            <div class="menu-group-title">DATA</div>
            <div class="nav-item">
                <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.index') ? 'active' : '' }}">
                    <i class="menu-icon fa-solid fa-users"></i>
                    <span class="menu-text">Data Karyawan</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('divisions.index') }}" class="nav-link {{ request()->routeIs('divisions.index') ? 'active' : '' }}">
                    <i class="menu-icon fa-solid fa-sitemap"></i>
                    <span class="menu-text">Divisi</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('positions.index') }}" class="nav-link {{ request()->routeIs('positions.index') ? 'active' : '' }}">
                    <i class="menu-icon fa-solid fa-briefcase"></i>
                    <span class="menu-text">Jabatan</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('banks.index') }}" class="nav-link {{ request()->routeIs('banks.index') ? 'active' : '' }}">
                    <i class="menu-icon fa-solid fa-building-columns"></i>
                    <span class="menu-text">Bank</span>
                </a>
            </div>
        </div>

        <!-- AKTIVITAS GROUP -->
        <div class="menu-group">
            <div class="menu-group-title">AKTIVITAS</div>
            <div class="nav-item">
                <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                    <i class="menu-icon fa-solid fa-fingerprint"></i>
                    <span class="menu-text">Absensi</span>
                </a>
            </div>
        </div>

        <!-- LAPORAN GROUP -->
        <div class="menu-group">
            <div class="menu-group-title">LAPORAN</div>
            <div class="nav-item">
                <a href="{{ route('report.attendance') }}" class="nav-link {{ request()->routeIs('report.attendance') ? 'active' : '' }}">
                    <i class="menu-icon fa-solid fa-file-lines"></i>
                    <span class="menu-text">Laporan Absensi</span>
                </a>
            </div>
        </div>

        <!-- PENGATURAN GROUP -->
        <div class="menu-group">
            <div class="menu-group-title">PENGATURAN</div>

            <!-- MANAJEMEN USER DENGAN SUBMENU -->
            <div class="nav-item has-submenu {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'open active-parent' : '' }}">
                <a href="javascript:void(0)" class="nav-link submenu-toggle">
                    <i class="menu-icon fa-solid fa-user-lock"></i>
                    <span class="menu-text">Manajemen User</span>
                    <i class="submenu-arrow fa-solid fa-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <li class="submenu-item">
                        <a href="{{ route('users.index') }}" class="submenu-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                            <i class="submenu-bullet fa-solid fa-circle"></i>
                            <span>User</span>
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="{{ route('roles.index') }}" class="submenu-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                            <i class="submenu-bullet fa-solid fa-circle"></i>
                            <span>Role</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-item">
                <a href="{{ route('setting.index') }}" class="nav-link {{ request()->routeIs('setting.index') ? 'active' : '' }}">
                    <i class="menu-icon fa-solid fa-gear"></i>
                    <span class="menu-text">Pengaturan</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('activity-log.index') }}"
                    class="nav-link {{ request()->routeIs('activity-log.index') ? 'active' : '' }}">
                    <i class="menu-icon fa-solid fa-clock-rotate-left"></i>
                    <span class="menu-text">Log Aktivitas</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Di sidebar bagian footer --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                <i class="menu-icon fa-solid fa-arrow-right-from-bracket"></i>
                <span class="menu-text">Logout</span>
            </a>
        </form>
    </div>
</nav>
