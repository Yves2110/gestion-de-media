@extends('mails.layout')

@section('title', 'Compte activé')

@section('content')
    <p style="margin:0 0 8px;font-size:22px;font-weight:700;color:#eb3349;">Bonjour {{ $user->firstname }},</p>
    <p style="margin:0 0 24px;line-height:1.6;">
        Votre demande d'inscription a été validée par un administrateur. Vous pouvez dès maintenant vous connecter à votre espace membre avec l'adresse <strong>{{ $user->email }}</strong> et le mot de passe que vous avez choisi lors de l'inscription.
    </p>

    <p style="margin:0 0 24px;line-height:1.6;">Accédez au catalogue de documents, audios et vidéos dès votre connexion.</p>

    <p style="margin:0;text-align:center;">
        <a href="{{ $loginUrl }}" style="display:inline-block;background:linear-gradient(135deg,#eb3349,#f45c43);color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;">
            Se connecter
        </a>
    </p>
@endsection
