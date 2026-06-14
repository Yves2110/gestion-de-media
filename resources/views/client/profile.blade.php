@extends('layouts.client')
@section('title', 'Mon profil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        @include('components.flash-messages')
        <div class="card mb-3">
            <div class="card-header"><h5 class="mb-0">Informations personnelles</h5></div>
            <div class="card-body">
                <form action="{{ route('catalogue.profile.update') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="firstname" class="form-control" value="{{ old('firstname', Auth::user()->firstname) }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Nom</label>
                        <input type="text" name="lastname" class="form-control" value="{{ old('lastname', Auth::user()->lastname) }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Mot de passe</h5></div>
            <div class="card-body">
                <form action="{{ route('catalogue.profile.password') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="form-control" required>
                        @error('current_password')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">8 caractères minimum, majuscule, minuscule et chiffre.</small>
                        @error('password')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Confirmer</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
