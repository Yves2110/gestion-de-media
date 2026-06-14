@extends('layouts.auth')
@section('title', 'Connexion')
@section('auth_card_class', 'auth-form-card-compact')

@section('auth_content')
<x-auth-form-brand title="Connexion" subtitle="Accédez à votre espace membre" />
@include('components.flash-messages')
<form action="{{ route('login.attempt') }}" method="post">
    @csrf
    <div class="mb-2">
        <label class="form-label">Email</label>
        <input type="email" class="form-control form-control-sm" name="email" value="{{ old('email') }}" placeholder="votre@email.com" required>
        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label mb-0">Mot de passe</label>
            <a href="{{ route('forget.password.get') }}" class="small">Oublié ?</a>
        </div>
        <input type="password" class="form-control form-control-sm" name="password" required>
        @error('password')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary btn-sm w-100 auth-submit-btn">Se connecter</button>
</form>
<div class="auth-form-footer text-center">
    <small class="text-muted">Pas de compte ? <a href="{{ route('register') }}">Créer un compte</a> | <a href="{{ route('home') }}">Accueil</a></small>
</div>
@endsection
