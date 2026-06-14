@extends('layouts.dashboard')
@section('title', 'Éditer utilisateur')
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            <div class="card col-md-8 offset-md-2">
                <div class="card-body">
                    <h4>Modifier {{ $user->firstname }} {{ $user->lastname }}</h4>
                    <form action="{{ route('userManage.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-2">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}" required>
                            @error('firstname')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Nom</label>
                            <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" required>
                            @error('lastname')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        @if (Auth::user()->isSuperAdmin())
                        <div class="mb-2">
                            <label class="form-label">Rôle</label>
                            <select name="role_id" class="form-select">
                                <option value="1" @selected($user->role_id == 1)>Super Administrateur</option>
                                <option value="2" @selected($user->role_id == 2)>Administrateur</option>
                            </select>
                        </div>
                        @endif
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('userManage') }}" class="btn btn-secondary">Retour</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('dashboard.components.footer')
@endsection
