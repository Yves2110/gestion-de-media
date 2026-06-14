<!DOCTYPE html>
<html>
<head>
    <title>Bienvenue | Gestion Media</title>
    @include('components.favicon')
</head>
<body>
    <h2>Bienvenue sur Gestion Media, {{ $user->firstname }} !</h2>
    <p>Votre compte client a été créé avec succès.</p>
    <p>Connectez-vous pour accéder au catalogue de médias et documents.</p>
    <p><a href="{{ url('/') }}">Se connecter</a></p>
</body>
</html>
