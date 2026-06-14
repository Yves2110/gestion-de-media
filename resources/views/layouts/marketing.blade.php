<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Accueil') | Gestion Media</title>
    @include('components.favicon')

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('app-assets/css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @stack('styles')

</head>

<body class="marketing-page portal-page">

    <header class="portal-header sticky-top">

        <div class="container-fluid px-4">

            <nav class="portal-nav navbar navbar-expand-lg py-0">

                <button class="navbar-toggler border-0 my-2" type="button" data-bs-toggle="collapse" data-bs-target="#portalNav">

                    <span class="navbar-toggler-icon"></span>

                </button>

                <div class="collapse navbar-collapse" id="portalNav">

                    <ul class="navbar-nav portal-menu align-items-lg-center">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}#publications">Dernières publications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home', ['type' => 'documents']) }}#publications">Documents</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home', ['type' => 'videos']) }}#publications">Vidéos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home', ['type' => 'audios']) }}#publications">Audios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('learning.*') ? 'active' : '' }}" href="{{ route('learning.index') }}">Espace d'apprentissage</a>
                        </li>
                        <x-contrib-menu align="menu" />

                        @auth

                            @if (auth()->user()->role_id === 3)

                                <li class="nav-item">

                                    <a class="nav-link" href="{{ route('catalogue.index') }}">Catalogue</a>

                                </li>

                            @endif

                        @endauth

                    </ul>

                    <ul class="navbar-nav portal-menu ms-lg-auto">

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Espace membre</a>

                            <ul class="dropdown-menu dropdown-menu-end portal-dropdown portal-dropdown-member">

                                @guest

                                    <li class="dropdown-header">Accès</li>

                                    <li><a class="dropdown-item" href="{{ route('login') }}">Se connecter</a></li>

                                    <li><a class="dropdown-item" href="{{ route('register') }}">Créer un compte</a></li>

                                @else

                                    <li class="dropdown-header">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</li>

                                    @if (auth()->user()->role_id === 3)

                                        <li><a class="dropdown-item" href="{{ route('catalogue.profile') }}">Mon profil</a></li>

                                        <li><a class="dropdown-item" href="{{ route('catalogue.index') }}">Mon catalogue</a></li>

                                    @else

                                        <li><a class="dropdown-item" href="{{ route('profile') }}">Mon profil</a></li>

                                        <li><a class="dropdown-item" href="{{ route('dashboard') }}">Administration</a></li>

                                    @endif

                                    <li><hr class="dropdown-divider"></li>

                                    <li>

                                        <form action="{{ route('logout') }}" method="POST">

                                            @csrf

                                            <button type="submit" class="dropdown-item portal-dropdown-logout">Se déconnecter</button>

                                        </form>

                                    </li>

                                @endguest

                            </ul>

                        </li>

                    </ul>

                </div>

            </nav>

        </div>

    </header>



    <main>

        @include('components.flash-messages')

        @yield('content')

    </main>



    <footer class="portal-footer py-4">

        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">

            <span>@include('components.site-copyright')</span>

            <div class="d-flex gap-3">

                <a href="{{ route('login') }}" class="text-white-50 text-decoration-none">Connexion</a>

                <a href="{{ route('register') }}" class="text-white-50 text-decoration-none">Inscription</a>

            </div>

        </div>

    </footer>



    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>

    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            if (typeof feather !== 'undefined') {

                feather.replace({ width: 16, height: 16 });

            }

        });

    </script>

    @stack('scripts')

</body>

</html>

