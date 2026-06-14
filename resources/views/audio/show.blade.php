@extends('layouts.dashboard')
@section('title', 'Audio | ' . $audio->title)
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            @include('components.flash-messages')
            <div class="card">
                <div class="card-header"><h4 class="mb-0">{{ $audio->title }}</h4></div>
                <div class="card-body">
                    @include('components.admin-content-actions', [
                        'item' => $audio,
                        'isPublished' => (bool) $audio->statut,
                        'previewUrl' => route('public.audios.show', $audio->id),
                        'editUrl' => route('audios.edit', $audio->id),
                        'destroyUrl' => route('audios.destroy', $audio->id),
                        'activateUrl' => route('audios.activate', $audio->id),
                        'deactivateUrl' => route('audios.desactivate', $audio->id),
                        'reportUrl' => route('audios.report', $audio->id),
                        'backUrl' => route('audios.index'),
                        'modalId' => 'reportAudio' . $audio->id,
                        'localisationUrl' => $audio->localisation ? null : route('audios.localisation', $audio->id),
                        'localisationDestroyUrl' => $audio->localisation ? route('audios.localisation.destroy', $audio->id) : null,
                    ])

                    <div class="row g-2 document-read-meta mb-3">
                        <div class="col-sm-6"><p class="mb-0"><strong>Auteur :</strong> {{ $audio->auteur }}</p></div>
                        <div class="col-sm-6"><p class="mb-0"><strong>Source :</strong> {{ $audio->source->label ?? 'Non renseignée' }}</p></div>
                        <div class="col-sm-6"><p class="mb-0"><strong>Vues catalogue :</strong> {{ $viewCount ?? 0 }}</p></div>
                        <div class="col-sm-6">
                            <p class="mb-0"><strong>Statut :</strong>
                                @if ($audio->statut)
                                    <span class="badge bg-success">Publié</span>
                                @else
                                    <span class="badge bg-secondary">Brouillon</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($audio->thumbnail_url)
                        <div class="mb-3">
                            <h6>Couverture</h6>
                            <img src="{{ $audio->thumbnail_url }}" class="rounded object-fit-cover" width="120" height="120" alt="Couverture">
                        </div>
                    @endif

                    @if ($audio->description)
                        <div class="mb-3">
                            <h6>Description</h6>
                            <p class="document-read-description mb-0">{{ $audio->description }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <h6>Lecteur</h6>
                        <div class="p-3 bg-light rounded"><x-safe-media :content="$audio->media" /></div>
                    </div>

                    @if ($audio->localisation)
                        <div class="mb-0">
                            <h6>Localisation</h6>
                            <x-safe-localisation :content="$audio->localisation" />
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
