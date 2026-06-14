@extends('mails.layout')

@section('title', 'Nouvelle demande d\'inscription')

@section('content')
    <p style="margin:0 0 8px;font-size:22px;font-weight:700;color:#eb3349;">Nouvelle inscription</p>
    <p style="margin:0 0 24px;line-height:1.6;">Une nouvelle personne a demandé l'accès au catalogue. Voici ses informations :</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;font-size:15px;">
        <tr>
            <td style="padding:8px 0;color:#666;width:120px;">Nom</td>
            <td style="padding:8px 0;font-weight:600;">{{ $user->firstname }} {{ $user->lastname }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#666;">Email</td>
            <td style="padding:8px 0;font-weight:600;">{{ $user->email }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#666;">Date</td>
            <td style="padding:8px 0;">{{ $user->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <p style="margin:0 0 24px;line-height:1.6;">Connectez-vous à l'espace admin pour valider ou refuser cette demande.</p>

    <p style="margin:0;text-align:center;">
        <a href="{{ $reviewUrl }}" style="display:inline-block;background:linear-gradient(135deg,#eb3349,#f45c43);color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;">
            Voir les inscriptions en attente
        </a>
    </p>
@endsection
