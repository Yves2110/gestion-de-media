<aside class="admin-sidebar" id="adminSidebar">
    <nav class="sidebar-nav py-3">
        <div class="sidebar-group">
            <small class="sidebar-group-label">Accueil</small>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i data-feather="home"></i> Dashboard
            </a>
        </div>
        <div class="sidebar-group">
            <small class="sidebar-group-label">Contenus</small>
            <a href="{{ route('audios.index') }}" class="sidebar-link {{ request()->routeIs('audios.*') ? 'active' : '' }}">
                <i data-feather="volume-2"></i> Audios
            </a>
            <a href="{{ route('videos.index') }}" class="sidebar-link {{ request()->routeIs('videos.*') ? 'active' : '' }}">
                <i data-feather="video"></i> Vidéos
            </a>
            <a href="{{ route('documents.index') }}" class="sidebar-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                <i data-feather="file-text"></i> Documents
                @if (($draftCount ?? 0) > 0)
                    <span class="badge bg-warning ms-auto">{{ $draftCount }}</span>
                @endif
            </a>
        </div>
        <div class="sidebar-group">
            <small class="sidebar-group-label">Organisation</small>
            <a href="{{ route('source.index') }}" class="sidebar-link {{ request()->routeIs('source.*') ? 'active' : '' }}">
                <i data-feather="paperclip"></i> Sources
            </a>
            <a href="{{ route('thematique.index') }}" class="sidebar-link {{ request()->routeIs('thematique.*') ? 'active' : '' }}">
                <i data-feather="archive"></i> Thématiques
            </a>
            <a href="{{ route('category.index') }}" class="sidebar-link {{ request()->routeIs('category.*') ? 'active' : '' }}">
                <i data-feather="folder"></i> Catégories
            </a>
        </div>
        @can('manage-admins')
        <div class="sidebar-group">
            <small class="sidebar-group-label">Administration</small>
            <a href="{{ route('userManage') }}" class="sidebar-link {{ request()->routeIs('userManage*') ? 'active' : '' }}">
                <i data-feather="users"></i> Utilisateurs
            </a>
        </div>
        @endcan
        <div class="sidebar-group">
            <small class="sidebar-group-label">Validations</small>
            <a href="{{ route('admin.registrations.index') }}" class="sidebar-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}">
                <i data-feather="user-check"></i> Inscriptions
                @if (($pendingRegistrationsCount ?? 0) > 0)
                    <span class="badge bg-warning ms-auto">{{ $pendingRegistrationsCount }}</span>
                @endif
            </a>
            <a href="{{ route('documents.index', ['status' => 'submissions']) }}" class="sidebar-link {{ request('status') === 'submissions' ? 'active' : '' }}">
                <i data-feather="inbox"></i> Soumissions docs
                @if (($pendingSubmissionsCount ?? 0) > 0)
                    <span class="badge bg-info ms-auto">{{ $pendingSubmissionsCount }}</span>
                @endif
            </a>
        </div>
        <div class="sidebar-group">
            <small class="sidebar-group-label">Compte</small>
            <a href="{{ route('profile') }}" class="sidebar-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                <i data-feather="user"></i> Mon profil
            </a>
        </div>
    </nav>
</aside>
