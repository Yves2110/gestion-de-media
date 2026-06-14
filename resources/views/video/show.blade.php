@extends('layouts.dashboard')
@section('title', 'Vidéo | ' . $video->title)
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            @include('components.flash-messages')
            <div class="card">
                <div class="card-header"><h4 class="mb-0">{{ $video->title }}</h4></div>
                <div class="card-body">
                    @include('components.admin-content-actions', [
                        'item' => $video,
                        'isPublished' => (bool) $video->statut,
                        'previewUrl' => route('public.videos.show', $video),
                        'editUrl' => route('videos.edit', $video),
                        'destroyUrl' => route('videos.destroy', $video),
                        'activateUrl' => route('videos.activate', $video->id),
                        'deactivateUrl' => route('videos.desactivate', $video->id),
                        'reportUrl' => route('videos.report', $video->id),
                        'backUrl' => route('videos.index'),
                        'modalId' => 'reportVideo' . $video->id,
                        'localisationUrl' => $video->localisation ? null : route('videos.localisation', $video->id),
                        'localisationDestroyUrl' => $video->localisation ? route('videos.localisation.destroy', $video->id) : null,
                    ])

                    <div class="row g-2 document-read-meta mb-3">
                        <div class="col-sm-6"><p class="mb-0"><strong>Auteur :</strong> {{ $video->auteur }}</p></div>
                        <div class="col-sm-6"><p class="mb-0"><strong>Source :</strong> {{ $video->source->label ?? 'Non renseignée' }}</p></div>
                        <div class="col-sm-6"><p class="mb-0"><strong>Vues catalogue :</strong> {{ $viewCount ?? 0 }}</p></div>
                        <div class="col-sm-6">
                            <p class="mb-0"><strong>Statut :</strong>
                                @if ($video->statut)
                                    <span class="badge bg-success">Publiée</span>
                                @else
                                    <span class="badge bg-secondary">Brouillon</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($video->thumbnail_url)
                        <div class="mb-3">
                            <h6>{{ $video->picture ? 'Couverture' : 'Miniature' }}</h6>
                            <img src="{{ $video->thumbnail_url }}" class="rounded object-fit-cover" width="160" height="90" alt="Couverture">
                        </div>
                    @endif

                    @if ($video->description)
                        <div class="mb-3">
                            <h6>Description</h6>
                            <p class="document-read-description mb-0">{{ $video->description }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <h6>Lecteur</h6>
                        <x-video-player :content="$video->media" />
                    </div>

                    @if ($video->localisation)
                        <div class="mb-0">
                            <h6>Localisation</h6>
                            <x-safe-localisation :content="$video->localisation" />
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
