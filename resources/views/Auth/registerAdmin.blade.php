@extends('layouts.dashboard')
@section('title', 'Ajouter administrateur')
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="card col-md-6 offset-md-3 mt-3">
            <div class="card-body">
                @if ($message = Session::get('message'))
                    <div class="alert alert-success">{{ $message }}</div>
                @endif
                <h4>Ajout d'administrateur</h4>
                <form action="{{ route('admin.store') }}" method="post">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required>
                        @error('firstname')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Prénom(s)</label>
                        <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required>
                        @error('lastname')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                    <a class="btn btn-outline-primary" href="{{ route('userManage') }}">Retour</a>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@include('dashboard.components.footer')
@endsection
