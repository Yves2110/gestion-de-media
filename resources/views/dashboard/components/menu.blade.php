<div class="horizontal-menu-wrapper">
    <div class="header-navbar navbar-expand-sm navbar navbar-horizontal floating-nav navbar-light navbar-shadow menu-border container-xxl" role="navigation" data-menu="menu-wrapper" data-menu-type="floating-nav">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto">
                    <a class="navbar-brand" href="{{ route('dashboard') }}">
                        <h2 class="brand-text mb-0">Gestion Media</h2>
                    </a>
                </li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="navbar-container main-menu-content" data-menu="menu-container">
            <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('dashboard') }}">
                        <i data-feather="home"></i><span>Dashboard</span>
                    </a>
                </li>
                @can('manage-admins')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('userManage') }}">
                        <i data-feather="users"></i><span>Gestion d'utilisateurs</span>
                    </a>
                </li>
                @endcan
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('source.index') }}">
                        <i data-feather="paperclip"></i><span>Sources</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('thematique.index') }}">
                        <i data-feather="archive"></i><span>Thématiques</span>
                    </a>
                </li>
                <li class="dropdown nav-item" data-menu="dropdown">
                    <a class="dropdown-toggle nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                        <i data-feather="image"></i><span>Médias</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('audios.index') }}"><i data-feather="volume-2"></i> Audio</a></li>
                        <li><a class="dropdown-item" href="{{ route('videos.index') }}"><i data-feather="video"></i> Vidéo</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('documents.index') }}">
                        <i data-feather="folder"></i><span>Documents</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
