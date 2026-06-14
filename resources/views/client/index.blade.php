@extends('layouts.client')
@section('title', 'Accueil')

@section('content')
<div class="mb-4">
    <form action="{{ route('catalogue.index') }}" method="GET" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="q" class="form-control" placeholder="Rechercher un titre, auteur..." value="{{ $query }}">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Rechercher</button>
        </div>
    </form>
</div>

<h5 class="mb-3">Audios récents</h5>
<div class="row mb-4">
    @forelse ($recentAudios as $audio)
        @include('client.partials.media-card', ['item' => $audio, 'type' => 'audio'])
    @empty
        @include('components.empty-state', [
            'title' => 'Aucun audio publié',
            'message' => 'Revenez bientôt pour découvrir de nouveaux contenus.',
        ])
    @endforelse
</div>

<h5 class="mb-3">Vidéos récentes</h5>
<div class="row mb-4">
    @forelse ($recentVideos as $video)
        @include('client.partials.media-card', ['item' => $video, 'type' => 'video'])
    @empty
        @include('components.empty-state', ['title' => 'Aucune vidéo publiée', 'message' => ''])
    @endforelse
</div>

<h5 class="mb-3">Documents récents</h5>
<div class="row">
    @forelse ($recentDocuments as $document)
        @include('client.partials.media-card', ['item' => $document, 'type' => 'document'])
    @empty
        @include('components.empty-state', ['title' => 'Aucun document publié', 'message' => ''])
    @endforelse
</div>
@endsection
