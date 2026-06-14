<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('components.favicon')
    <title>@yield('title', 'Connexion') | Gestion Media</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="auth-page @yield('auth_page_class')">
    <div class="auth-page-inner">
        <aside class="auth-decoration-panel">
            @include('components.auth-library-decoration')
        </aside>
        <main class="auth-form-panel">
            <div class="auth-form-card @yield('auth_card_class')">
                @yield('auth_content')
            </div>
        </main>
    </div>
    <script src="{{ asset('assets/js/password-toggle.js') }}"></script>
</body>
</html>
