<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text mx-3">StaffWork</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        LMS
    </div>

    <!-- Nav Item - Materi -->
    <li class="nav-item {{ request()->routeIs('materis.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('materis.index') }}">
            <i class="fas fa-fw fa-book-open"></i>
            <span>Materi</span>
        </a>
    </li>

    <!-- Nav Item - Tugas & Ujian -->
    <li class="nav-item {{ request()->routeIs('exams.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('exams.index') }}">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Tugas & Ujian</span>
        </a>
    </li>

    @if (Auth::user()->isSuperAdmin())
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Super Admin
        </div>

        <!-- Nav Item - User Management -->
        <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a class="nav-link {{ request()->routeIs('users.*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseUsers"
                aria-expanded="{{ request()->routeIs('users.*') ? 'true' : 'false' }}" aria-controls="collapseUsers">
                <i class="fas fa-fw fa-users-cog"></i>
                <span>Kelola Akun</span>
            </a>
            <div id="collapseUsers" class="collapse {{ request()->routeIs('users.*') ? 'show' : '' }}" aria-labelledby="headingUsers" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Registrasi & Peran:</h6>
                    <a class="collapse-item {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-list fa-sm mr-1"></i> Daftar Akun
                    </a>
                    <a class="collapse-item {{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}">
                        <i class="fas fa-user-plus fa-sm mr-1"></i> Registrasi Akun
                    </a>
                </div>
            </div>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Akun Saya
    </div>

    <!-- Nav Item - Profile -->
    <li class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('profile.edit') }}">
            <i class="fas fa-fw fa-user-circle"></i>
            <span>Profil & Password</span>
        </a>
    </li>

    <!-- Nav Item - Logout -->
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Keluar Akun</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
