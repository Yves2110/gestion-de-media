@extends('layouts.dashboard')
@section('title', 'Documents')
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            @include('components.flash-messages')
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h4 class="mb-0">Gestion des documents</h4>
                    <div class="d-flex flex-wrap gap-1">
                        <a href="{{ route('documents.index', ['status' => 'published']) }}" class="btn btn-sm btn-outline-success">Publiés</a>
                        <a href="{{ route('documents.index', ['status' => 'draft']) }}" class="btn btn-sm btn-outline-warning">Brouillons</a>
                        <a href="{{ route('documents.index', ['status' => 'submissions']) }}" class="btn btn-sm btn-outline-info">Soumissions publiques</a>
                        <a href="{{ route('documents.index') }}" class="btn btn-sm btn-outline-secondary">Tous</a>
                        <a href="{{ route('documents.create') }}" class="btn btn-primary btn-sm">Ajouter un document</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Couverture</th>
                                    <th>Titre</th>
                                    <th>Auteur</th>
                                    <th>Source</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($documents as $document)
                                <tr>
                                    <td>
                                        @if ($document->picture)
                                            <img src="{{ asset('storage/picture/' . $document->picture) }}" width="50" height="50" class="rounded object-fit-cover" alt="couverture">
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $document->title }}
                                        @if ($document->is_guest_submission)
                                            <br><small class="text-info">Soumis par {{ $document->submitter_name }} ({{ $document->submitter_email }})</small>
                                        @endif
                                    </td>
                                    <td>{{ $document->auteur }}</td>
                                    <td>{{ $document->source->label ?? '-' }}</td>
                                    <td>
                                        @if ($document->statut_publication)
                                            <span class="badge bg-success">Publié</span>
                                        @elseif ($document->is_guest_submission)
                                            <span class="badge bg-info">En validation</span>
                                        @else
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <div class="admin-row-actions">
                                            @if (!$document->statut_publication)
                                                <x-icon-action icon="check-circle" title="Valider / Publier" action="{{ route('documents.activate', $document->id) }}" variant="success" />
                                            @else
                                                <x-icon-action icon="eye-off" title="Dépublier" action="{{ route('documents.desactivate', $document->id) }}" variant="warning" />
                                            @endif
                                            <x-icon-action icon="eye" title="Aperçu public" href="{{ route('public.documents.show', $document) }}" variant="info" target="_blank" />
                                            <x-icon-action icon="download" title="Télécharger PDF" href="{{ route('documents.download', $document) }}" variant="info" />
                                            <x-icon-action icon="edit-2" title="Éditer" href="{{ route('documents.edit', $document) }}" variant="primary" />
                                            <x-icon-action icon="trash-2" title="Supprimer" action="{{ route('documents.destroy', $document) }}" method="DELETE" variant="danger" confirm="Supprimer ce document ?" />
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        @include('components.empty-state', [
                                            'title' => 'Aucun document',
                                            'message' => 'Importez votre premier document PDF.',
                                            'actionUrl' => route('documents.create'),
                                            'actionLabel' => 'Ajouter un document',
                                        ])
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('dashboard.components.footer')
@push('scripts')
<script>if (typeof feather !== 'undefined') feather.replace({ width: 16, height: 16 });</script>
@endpush
@endsection
