@extends('layouts.dashboard')
@section('title', 'Gestion utilisateurs')
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Administrateurs</h4>
                    <a href="{{ route('addAdmin') }}" class="btn btn-primary">Ajouter un administrateur</a>
                </div>
                <div class="card-body">
                    @if ($success = Session::get('success'))
                        <div class="alert alert-success">{{ $success }}</div>
                    @endif
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Prénom</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                            <tr>
                                <td>{{ $admin->firstname }}</td>
                                <td>{{ $admin->lastname }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->role->label }}</td>
                                <td>{{ $admin->statut ? 'Actif' : 'Inactif' }}</td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('userManage.edit', $admin) }}" class="btn btn-sm btn-primary">Éditer</a>
                                    @if ($admin->id !== Auth::id())
                                        @if ($admin->statut)
                                            <form action="{{ route('desactivate', $admin->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">Désactiver</button>
                                            </form>
                                        @else
                                            <form action="{{ route('activate', $admin->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Activer</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('removeManager', $admin->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $admins->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('dashboard.components.footer')
@endsection
