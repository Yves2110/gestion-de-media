@extends('layouts.dashboard')
@section('title', 'Inscriptions en attente')
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            @include('components.flash-messages')
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Demandes d'inscription à valider</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendingUsers as $user)
                                <tr>
                                    <td>{{ $user->firstname }}</td>
                                    <td>{{ $user->lastname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="d-flex gap-1 align-items-center">
                                        <x-icon-action icon="check" title="Valider l'inscription" action="{{ route('admin.registrations.approve', $user) }}" variant="success" />
                                        <x-icon-action icon="x" title="Supprimer la demande" action="{{ route('admin.registrations.reject', $user) }}" variant="danger" confirm="Supprimer cette demande d'inscription ?" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        @include('components.empty-state', [
                                            'title' => 'Aucune inscription en attente',
                                            'message' => 'Les nouvelles demandes apparaîtront ici.',
                                        ])
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $pendingUsers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('dashboard.components.footer')
@endsection
