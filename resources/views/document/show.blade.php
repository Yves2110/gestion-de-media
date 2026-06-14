@extends('layouts.dashboard')
@section('title', 'Document | ' . $document->title)
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            @include('components.flash-messages')
            <div class="card">
                <div class="card-header"><h4 class="mb-0">{{ $document->title }}</h4></div>
                <div class="card-body">
                    @include('components.admin-content-actions', [
                        'item' => $document,
                        'isPublished' => (bool) $document->statut_publication,
                        'previewUrl' => route('public.documents.show', $document),
                        'editUrl' => route('documents.edit', $document),
                        'destroyUrl' => route('documents.destroy', $document),
                        'activateUrl' => route('documents.activate', $document->id),
                        'deactivateUrl' => route('documents.desactivate', $document->id),
                        'reportUrl' => route('documents.report', $document->id),
                        'backUrl' => route('documents.index'),
                        'modalId' => 'reportDocument' . $document->id,
                        'localisationUrl' => $document->localisation ? null : route('documents.localisation', $document->id),
                        'localisationDestroyUrl' => $document->localisation ? route('documents.localisation.destroy', $document->id) : null,
                    ])

                    <div class="row g-2 document-read-meta mb-3">
                        <div class="col-sm-6"><p class="mb-0"><strong>Auteur :</strong> {{ $document->auteur }}</p></div>
                        <div class="col-sm-6"><p class="mb-0"><strong>Source :</strong> {{ $document->source->label ?? 'Non renseignée' }}</p></div>
                        <div class="col-sm-6"><p class="mb-0"><strong>Catégorie :</strong> {{ $document->categorie }}</p></div>
                        <div class="col-sm-6"><p class="mb-0"><strong>Pages :</strong> {{ $document->page }}</p></div>
                        <div class="col-sm-6"><p class="mb-0"><strong>Vues catalogue :</strong> {{ $viewCount ?? 0 }}</p></div>
                        <div class="col-sm-6">
                            <p class="mb-0"><strong>Statut :</strong>
                                @if ($document->statut_publication)
                                    <span class="badge bg-success">Publié</span>
                                @else
                                    <span class="badge bg-secondary">Brouillon</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($document->resume)
                        <div class="mb-3">
                            <h6>Résumé</h6>
                            <p class="document-read-description mb-0">{{ $document->resume }}</p>
                        </div>
                    @endif

                    <a href="{{ route('documents.download', $document) }}" class="btn btn-primary btn-sm mb-3">
                        <i data-feather="download"></i> Télécharger le PDF
                    </a>

                    @if ($document->localisation)
                        <div class="mb-0">
                            <h6>Localisation</h6>
                            <x-safe-localisation :content="$document->localisation" />
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('dashboard.components.footer')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof feather !== 'undefined') feather.replace({ width: 14, height: 14 });
    });
</script>
@endpush
