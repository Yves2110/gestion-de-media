@props(['document', 'readUrl' => null, 'downloadUrl' => null])



@php

    $readUrl = $readUrl ?? route('public.documents.show', $document);

    $downloadUrl = $downloadUrl ?? route('public.documents.download', $document);

@endphp



<div class="col-6 col-md-4 col-lg-3 mb-4">

    <div class="document-library-card h-100">

        <div class="document-library-cover">

            @if ($document->picture)

                <img src="{{ asset('storage/picture/' . $document->picture) }}" alt="{{ $document->title }}">

            @else

                <div class="document-library-cover-placeholder">

                    <x-feather-icon name="file-text" :size="48" />

                </div>

            @endif

        </div>

        <div class="document-library-body">

            <h6 class="document-library-title">{{ $document->title }}</h6>

            <p class="document-library-meta mb-2">

                <x-feather-icon name="user" :size="12" />

                {{ $document->auteur }}

                <span class="mx-1">·</span>

                {{ $document->source->label ?? 'Non renseignée' }}

            </p>

            @if ($document->resume)

                <p class="document-library-resume">{{ Str::limit(strip_tags($document->resume), 140) }}</p>

            @else

                <p class="document-library-resume text-muted">Aucun résumé disponible.</p>

            @endif

            <div class="document-library-actions mt-auto d-flex gap-2">

                <a href="{{ $readUrl }}" class="btn btn-sm btn-primary flex-fill">

                    <x-feather-icon name="book-open" :size="14" /> Lire

                </a>

                <a href="{{ $downloadUrl }}" class="btn btn-sm btn-outline-primary flex-fill">

                    <x-feather-icon name="download" :size="14" /> Télécharger

                </a>

            </div>

        </div>

    </div>

</div>

