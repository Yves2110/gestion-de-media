@extends('layouts.marketing')
@section('title', $item->title)

@section('content')
<div class="container py-3">
    @if ($isPreview)
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
            <x-feather-icon name="eye" :size="18" />
            <span><strong>Aperçu administrateur</strong> — ce contenu est en brouillon et invisible pour le public.</span>
        </div>
    @endif

    <div class="card border-0 shadow-sm document-read-card content-read-compact">
        <div class="card-body">
            @if ($item->thumbnail_url)
                <img src="{{ $item->thumbnail_url }}" class="img-fluid rounded document-read-cover w-100 mb-3" alt="{{ $item->title }}">
            @endif
            <h3 class="content-read-title">{{ $item->title }}</h3>
            <p class="content-read-meta text-muted">
                {{ $item->auteur }}
                @if ($item->source)
                    | {{ $item->source->label }}
                @endif
                @if ($item->code_media)
                    | {{ $item->code_media }}
                @endif
            </p>

            @if ($item->description)
                <div class="content-read-block">
                    <h6 class="content-read-label">Description</h6>
                    <p class="document-read-description mb-0">{{ $item->description }}</p>
                </div>
            @endif

            <div class="content-read-block">
                @if ($type === 'video')
                    <x-video-player :content="$item->media" />
                @else
                    <div class="p-2 bg-light rounded">
                        <x-safe-media :content="$item->media" />
                    </div>
                @endif
            </div>

            @if ($item->localisation)
                <div class="content-read-block">
                    <h6 class="content-read-label">Localisation</h6>
                    <x-safe-localisation :content="$item->localisation" />
                </div>
            @endif

            <div class="content-read-actions d-flex flex-wrap gap-2">
                @if ($item->statut)
                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#reportModal">
                        <x-feather-icon name="flag" :size="14" /> Signaler
                    </button>
                @endif

                @auth
                    @if (auth()->user()->isAdmin())
                        @if ($item->statut)
                            <form action="{{ $type === 'audio' ? route('audios.desactivate', $item->id) : route('videos.desactivate', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <x-feather-icon name="eye-off" :size="14" /> Dépublier
                                </button>
                            </form>
                        @else
                            <form action="{{ $type === 'audio' ? route('audios.activate', $item->id) : route('videos.activate', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    <x-feather-icon name="check-circle" :size="14" /> Publier
                                </button>
                            </form>
                        @endif
                        <a href="{{ $type === 'audio' ? route('audios.edit', $item->id) : route('videos.edit', $item->id) }}" class="btn btn-outline-primary btn-sm">
                            <x-feather-icon name="edit-2" :size="14" /> Modifier
                        </a>
                        <form action="{{ $type === 'audio' ? route('audios.destroy', $item->id) : route('videos.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer définitivement ce contenu ?')">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="redirect" value="public">
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <x-feather-icon name="trash-2" :size="14" /> Supprimer
                            </button>
                        </form>
                    @endif
                @endauth

                <a href="{{ route('home') }}#{{ $type === 'audio' ? 'audios' : 'videos' }}" class="btn btn-outline-secondary btn-sm">Retour</a>
            </div>
        </div>
    </div>
</div>

@if ($item->statut)
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ $type === 'audio' ? route('public.audios.report', $item) : route('public.videos.report', $item) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Signaler ce contenu</h5>
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
@endif

@if (session('message') || session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
        <div class="alert alert-success mb-0 shadow">
            {{ session('message') ?? session('success') }}
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof feather !== 'undefined') feather.replace({ width: 16, height: 16 });
    });
</script>
@endpush
