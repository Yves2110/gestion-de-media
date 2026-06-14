<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Catalogue') | Gestion Media</title>
    @include('components.favicon')
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
    <header class="client-header bg-primary text-white py-3">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('home') }}" class="text-white text-decoration-none"><h4 class="mb-0 text-white">Gestion Media</h4></a>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="small">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
                <a href="{{ route('catalogue.profile') }}" class="btn btn-sm btn-light">Profil</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">Déconnexion</button>
                </form>
            </div>
        </div>
    </header>

    <nav class="border-bottom mb-4 bg-white">
        <div class="container py-2">
            <a href="{{ route('catalogue.index') }}" class="me-3 text-decoration-none">Accueil</a>
            <a href="{{ route('catalogue.audios') }}" class="me-3 text-decoration-none">Audios</a>
            <a href="{{ route('catalogue.videos') }}" class="me-3 text-decoration-none">Vidéos</a>
            <a href="{{ route('catalogue.documents') }}" class="text-decoration-none">Documents</a>
        </div>
    </nav>

    <main class="container pb-5">
        @include('components.flash-messages')
        @yield('content')
    </main>

    <footer class="portal-footer py-3 mt-auto">
        <div class="container text-center">
            @include('components.site-copyright')
        </div>
    </footer>

    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof feather !== 'undefined') feather.replace({ width: 16, height: 16 });
        });
    </script>
    @stack('scripts')
</body>
</html>
