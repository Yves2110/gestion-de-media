@extends('layouts.dashboard')
@section('title', 'Recherche')
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <h4>Résultats pour « {{ $query }} »</h4>
                </div>
                <div class="card-body">
                    @if ($query === '')
                        <p class="text-muted">Saisissez un terme de recherche dans la barre du haut.</p>
                    @else
                        @if ($audios->isNotEmpty())
                            <h5 class="mt-2"><i data-feather="volume-2"></i> Audios ({{ $audios->count() }})</h5>
                            <div class="row g-2 mb-3">
                                @foreach ($audios as $audio)
                                    <div class="col-md-6">
                                        <a href="{{ route('audios.show', $audio) }}" class="card card-body text-decoration-none text-dark">
                                            <strong>{{ $audio->title }}</strong>
                                            <small class="text-muted">{{ $audio->auteur }}</small>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($videos->isNotEmpty())
                            <h5><i data-feather="video"></i> Vidéos ({{ $videos->count() }})</h5>
                            <div class="row g-2 mb-3">
                                @foreach ($videos as $video)
                                    <div class="col-md-6">
                                        <a href="{{ route('videos.show', $video) }}" class="card card-body text-decoration-none text-dark">
                                            <strong>{{ $video->title }}</strong>
                                            <small class="text-muted">{{ $video->auteur }}</small>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($documents->isNotEmpty())
                            <h5><i data-feather="file-text"></i> Documents ({{ $documents->count() }})</h5>
                            <div class="row g-2 mb-3">
                                @foreach ($documents as $document)
                                    <div class="col-md-6">
                                        <a href="{{ route('documents.show', $document) }}" class="card card-body text-decoration-none text-dark">
                                            <strong>{{ $document->title }}</strong>
                                            <small class="text-muted">{{ $document->auteur }}</small>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($sources->isNotEmpty())
                            <h5><i data-feather="paperclip"></i> Sources</h5>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach ($sources as $source)
                                    <a href="{{ route('source.edit', $source) }}" class="badge bg-light-primary p-2 text-decoration-none">{{ $source->label }}</a>
                                @endforeach
                            </div>
                        @endif

                        @if ($thematiques->isNotEmpty())
                            <h5><i data-feather="archive"></i> Thématiques</h5>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach ($thematiques as $thematique)
                                    <a href="{{ route('thematique.edit', $thematique) }}" class="badge bg-light-info p-2 text-decoration-none">{{ $thematique->label }}</a>
                                @endforeach
                            </div>
                        @endif

                        @if ($audios->isEmpty() && $videos->isEmpty() && $documents->isEmpty() && $sources->isEmpty() && $thematiques->isEmpty())
                            <p class="text-muted">Aucun résultat pour cette recherche.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('dashboard.components.footer')
@endsection
