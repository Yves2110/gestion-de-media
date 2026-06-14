@extends('mails.layout')

@section('title', 'Compte administrateur créé')

@section('content')
    <p style="margin:0 0 8px;font-size:22px;font-weight:700;color:#eb3349;">Bonjour {{ $firstname }},</p>
    <p style="margin:0 0 24px;line-height:1.6;">Votre compte administrateur a été créé avec succès.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;font-size:15px;">
        <tr>
            <td style="padding:8px 0;color:#666;width:140px;">Adresse e-mail</td>
            <td style="padding:8px 0;font-weight:600;">{{ $email }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#666;">Mot de passe temporaire</td>
            <td style="padding:8px 0;font-weight:600;font-family:monospace;">{{ $password }}</td>
        </tr>
    </table>

    <p style="margin:0 0 24px;line-height:1.6;">Connectez-vous et modifiez votre mot de passe dès la première connexion.</p>

    <p style="margin:0;text-align:center;">
        <a href="{{ route('login') }}" style="display:inline-block;background:linear-gradient(135deg,#eb3349,#f45c43);color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;">
            Se connecter
        </a>
    </p>
@endsection
