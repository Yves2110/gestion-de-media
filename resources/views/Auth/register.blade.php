@extends('layouts.auth')
@section('title', 'Inscription')
@section('auth_card_class', 'auth-form-card-compact auth-form-card-register')

@section('auth_content')
<x-auth-form-brand title="Créer un compte" subtitle="Accès au catalogue après validation" />
@include('components.flash-messages')
<x-validation-summary />
<form action="{{ route('register.store') }}" method="post" class="auth-register-form" novalidate>
    @csrf
    <div class="row auth-form-grid g-2">
        <div class="col-6">
            <label class="form-label">Nom</label>
            <input type="text" class="form-control form-control-sm" name="firstname" value="{{ old('firstname') }}" required>
            @error('firstname')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-6">
            <label class="form-label">Prénom(s)</label>
            <input type="text" class="form-control form-control-sm" name="lastname" value="{{ old('lastname') }}" required>
            @error('lastname')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-12">
            <label class="form-label">Email</label>
            <input type="email" class="form-control form-control-sm" name="email" value="{{ old('email') }}" placeholder="votre@email.com" required>
            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-6">
            <label class="form-label">Mot de passe</label>
            <x-password-input name="password" size="sm" required autocomplete="new-password" />
            <small class="text-muted">8 car. min., majuscule, minuscule et chiffre</small>
            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-6">
            <label class="form-label">Confirmation</label>
            <x-password-input name="confirm_password" size="sm" required autocomplete="new-password" />
            @error('confirm_password')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-sm w-100 auth-submit-btn">S'inscrire</button>
</form>
<div class="auth-form-footer text-center">
    <small class="text-muted">Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a> | <a href="{{ route('home') }}">Accueil</a></small>
</div>
@endsection