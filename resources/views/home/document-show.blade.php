@extends('layouts.marketing')
@section('title', $document->title)

@section('content')
<div class="container py-4">
    @if ($isPreview ?? false)
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
            <x-feather-icon name="eye" :size="18" />
            <span><strong>Aperçu administrateur</strong> — ce document est en brouillon et invisible pour le public.</span>
        </div>
    @endif

    <div class="card border-0 shadow-sm document-read-card">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-4">
                    @if ($document->picture)
                        <img src="{{ asset('storage/picture/' . $document->picture) }}" class="img-fluid rounded document-read-cover w-100" alt="{{ $document->title }}">
                    @else
                        <div class="document-read-cover-placeholder rounded d-flex align-items-center justify-content-center">
                            <x-feather-icon name="file-text" :size="48" />
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h3 class="mb-2">{{ $document->title }}</h3>
                    <p class="text-muted mb-3">{{ $document->auteur }} | {{ $document->source->label ?? 'Non renseignée' }}</p>

                    <div class="row g-2 document-read-meta">
                        <div class="col-sm-6">
                            <p class="mb-0"><strong>Catégorie :</strong> {{ $document->categorie }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0"><strong>Pages :</strong> {{ $document->page }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0"><strong>Date :</strong> {{ $document->publication_date?->format('d/m/Y') }}</p>
                        </div>
                        @if ($document->edition)
                            <div class="col-sm-6">
                                <p class="mb-0"><strong>Édition :</strong> {{ $document->edition }}</p>
                            </div>
                        @endif
                    </div>

                    @if ($document->resume)
                        <div class="document-read-resume mt-3">
                            <h6>Résumé</h6>
                            <p class="document-read-description mb-0">{!! nl2br(e($document->resume)) !!}</p>
                        </div>
                    @else
                        <p class="text-muted mt-3 mb-0">Aucune description disponible pour ce document.</p>
                    @endif

                    @if ($wordCount > 0 && $wordCount < 250)
                        <p class="text-warning small mt-2 mb-0">
                            <x-feather-icon name="alert-circle" :size="14" />
                            Description incomplète (minimum recommandé : 250 mots).
                        </p>
                    @endif

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="{{ route('public.documents.download', $document) }}" class="btn btn-primary btn-sm">
                            <x-feather-icon name="download" :size="14" /> Télécharger le PDF
                        </a>
                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#reportModal">
                            <x-feather-icon name="flag" :size="14" /> Signaler
                        </button>
                        @auth
                            @if (auth()->user()->isAdmin())
                                @if ($document->statut_publication)
                                    <form action="{{ route('documents.desactivate', $document->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                            <x-feather-icon name="eye-off" :size="14" /> Dépublier
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('documents.activate', $document->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                            <x-feather-icon name="check-circle" :size="14" /> Publier
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-outline-primary btn-sm">
                                    <x-feather-icon name="edit-2" :size="14" /> Modifier
                                </a>
                                <form action="{{ route('documents.destroy', $document->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer définitivement ce document ?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="redirect" value="public">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <x-feather-icon name="trash-2" :size="14" /> Supprimer
                                    </button>
                                </form>
                            @endif
                        @endauth
                        <a href="{{ route('home') }}#documents" class="btn btn-outline-secondary btn-sm">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (session('message'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
        <div class="alert alert-success mb-0 shadow">{{ session('message') }}</div>
    </div>
@endif

<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('public.documents.report', $document) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Signaler ce document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    @guest
                        <div class="mb-2">
                            <label class="form-label">Votre nom</label>
                            <input type="text" name="reporter_name" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Votre email</label>
                            <input type="email" name="reporter_email" class="form-control" required>
                        </div>
                    @endguest
                    <div class="mb-2">
                        <label class="form-label">Motif du signalement</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="Décrivez le problème constaté..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Envoyer le signalement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof feather !== 'undefined') feather.replace({ width: 16, height: 16 });
    });
</script>
@endpush
