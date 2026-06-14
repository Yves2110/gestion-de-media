@extends('layouts.client')
@section('title', $document->title)

@section('content')
<div class="card document-read-card">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-4">
                @if ($document->picture)
                    <img src="{{ asset('storage/picture/' . $document->picture) }}" class="img-fluid rounded document-read-cover" alt="{{ $document->title }}">
                @else
                    <div class="document-read-cover-placeholder rounded d-flex align-items-center justify-content-center">
                        <i data-feather="file-text"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <h3>{{ $document->title }}</h3>
                <p class="text-muted">{{ $document->auteur }} | {{ $document->source->label ?? 'Non renseignée' }}</p>
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
                @endif
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('public.documents.download', $document) }}" class="btn btn-primary">Télécharger le PDF</a>
                    <a href="{{ route('catalogue.documents') }}" class="btn btn-outline-secondary">Retour</a>
                </div>
            </div>
        </div>
        @if ($document->localisation)
            <hr>
            <h5>Localisation</h5>
            <x-safe-localisation :content="$document->localisation" />
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>if (typeof feather !== 'undefined') feather.replace({ width: 18, height: 18 });</script>
@endpush
