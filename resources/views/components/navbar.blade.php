<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Cari data sekolah..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        @php
            $unreadCount = Auth::user()->unreadNotifications()->count();
            $latestNotifications = Auth::user()->notifications()->latest('created_at')->take(5)->get();
        @endphp

        <!-- Nav Item - Notifications -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                @if ($unreadCount > 0)
                    <span class="badge badge-danger badge-counter">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </a>
            <!-- Dropdown - Notifications -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Notifikasi
                </h6>
                @forelse ($latestNotifications as $notification)
                    @php $data = $notification->data; @endphp
                    <a class="dropdown-item d-flex align-items-center {{ $notification->read_at ? '' : 'bg-light' }}"
                        href="{{ route('notifications.index') }}">
                        <div class="mr-3">
                            <div class="icon-circle {{ ($data['room'] ?? '') === 'bk' ? 'bg-danger' : ((($data['room'] ?? '') === 'guru') ? 'bg-warning' : 'bg-primary') }}">
                                <i class="fas {{ ($data['room'] ?? '') !== '' ? 'fa-bell text-white' : 'fa-info text-white' }}"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">{{ $notification->created_at?->format('d M Y, H:i') }}</div>
                            <span class="{{ $notification->read_at ? 'text-gray-800' : 'font-weight-bold' }}">
                                {{ $data['title'] ?? 'Notifikasi' }}
                            </span>
                            <div class="small text-gray-600">{{ \Illuminate\Support\Str::limit($data['message'] ?? '', 50) }}</div>
                        </div>
                    </a>
                @empty
                    <span class="dropdown-item-text text-muted small">Belum ada notifikasi.</span>
                @endforelse
                <a class="dropdown-item text-center small font-weight-bold text-primary" href="{{ route('notifications.index') }}">
                    Lihat Semua Notifikasi <i class="fas fa-arrow-right fa-sm ml-1"></i>
                </a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small font-weight-bold">
                    {{ Auth::user()->name ?? 'Pengguna' }}
                </span>
                <img class="img-profile rounded-circle border"
                    src="{{ Auth::user()->avatar_url }}" alt="Profile"
                    style="object-fit: cover; width: 32px; height: 32px;">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <div class="dropdown-item-text text-muted small pb-2 border-bottom">
                    <div class="font-weight-bold text-gray-800">{{ Auth::user()->name }}</div>
                    <div>{{ Auth::user()->email ?? '' }}</div>
                    <span class="badge {{ Auth::user()->role_badge_class }} mt-1">
                        {{ Auth::user()->role_name }}
                    </span>
                </div>
                <a class="dropdown-item mt-2" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil Saya
                </a>
                @if (Auth::user()->isSuperAdmin())
                    <a class="dropdown-item" href="{{ route('users.index') }}">
                        <i class="fas fa-users-cog fa-sm fa-fw mr-2 text-gray-400"></i>
                        Kelola Pengguna
                    </a>
                @endif
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                    Keluar (Logout)
                </a>
            </div>
        </li>

    </ul>

</nav>
