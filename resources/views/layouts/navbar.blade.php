<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-2 shadow-sm">
    <div class="container-fluid p-0">
        <button class="btn btn-outline-secondary btn-sm me-3" id="menu-toggle">
            <i class="bi bi-list fs-5"></i>
        </button>

        <span class="navbar-text fw-medium text-secondary d-none d-md-inline">
            Sistem Informasi Manajemen Berbasis Komponen Modular
        </span>

        <div class="ms-auto d-flex align-items-center">
            @auth
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-dark"
                       href="#"
                       role="button"
                       id="userProfileDropdown"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm"
                             style="width: 32px; height: 32px; font-size: 0.85rem;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="text-start d-none d-sm-inline">
                            <div class="fw-semibold lh-1" style="font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                            <small class="text-muted text-uppercase" style="font-size: 0.7rem;">{{ str_replace('_', ' ', Auth::user()->role) }}</small>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userProfileDropdown">
                        <li>
                            <div class="px-3 py-2 border-bottom">
                                <p class="text-sm text-dark mb-0 fw-medium">{{ Auth::user()->email }}</p>
                                <span class="badge bg-light text-dark text-xs border mt-1">Status: {{ Auth::user()->status }}</span>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-menu-item dropdown-item d-flex align-items-center gap-2 py-2 text-secondary" href="#" onclick="event.preventDefault(); alert('Fitur Profil Pengguna segera hadir.');">
                                <i class="bi bi-person-bounding-box text-primary"></i> Detail Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar?');">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Sign Out / Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>
