<nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center" data-nav="brand-center">
    <div class="navbar-header d-flex align-items-center">
        <button type="button" class="btn btn-sm btn-outline-primary d-lg-none me-2" id="sidebarToggle" aria-label="Menu">
            <i data-feather="menu"></i>
        </button>
        <a class="navbar-brand mb-0 text-decoration-none" href="{{ route('home') }}">
            <h2 class="brand-text mb-0 fs-4">Gestion Media</h2>
        </a>
    </div>
    <div class="navbar-container d-flex content flex-grow-1">
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item nav-search d-none d-md-block">
                <form action="{{ route('admin.search') }}" method="GET" class="d-flex">
                    <input class="form-control form-control-sm" type="text" name="q" placeholder="Rechercher..." value="{{ request('q') }}">
                    <button type="submit" class="btn btn-primary btn-sm ms-1"><i data-feather="search"></i></button>
                </form>
            </li>
            <li class="nav-item dropdown dropdown-user ms-2">
                <a class="nav-link dropdown-toggle dropdown-user-link" href="{{ route('profile') }}">
                    <span class="d-none d-sm-inline">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
                </a>
            </li>
            <li class="nav-item ms-2">
                <form action="{{ route('logout') }}" method="post" class="d-inline">
                    @csrf
                    <button class="btn btn-primary btn-sm" type="submit">Déconnexion</button>
                </form>
            </li>
        </ul>
    </div>
</nav>
