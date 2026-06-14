@extends('mails.layout')

@section('title', 'Réinitialisation de mot de passe')

@section('content')
    <p style="margin:0 0 8px;font-size:22px;font-weight:700;color:#eb3349;">Réinitialisation du mot de passe</p>
    <p style="margin:0 0 24px;line-height:1.6;">
        Vous avez demandé à réinitialiser votre mot de passe. Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe.
    </p>
    <p style="margin:0 0 24px;line-height:1.6;color:#666;font-size:14px;">
        Ce lien est valable 60 minutes. Si vous n'êtes pas à l'origine de cette demande, ignorez cet e-mail.
    </p>
    <p style="margin:0;text-align:center;">
        <a href="{{ route('reset.password.get', $token) }}" style="display:inline-block;background:linear-gradient(135deg,#eb3349,#f45c43);color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;">
            Réinitialiser mon mot de passe
        </a>
    </p>
@endsection
