@extends('layouts.marketing')
@section('title', $type === 'video' ? 'Contribuer une vidéo' : 'Contribuer un audio')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <div class="card shadow-sm border-0 contrib-form-compact">
                <div class="card-body">
                    <div class="mb-3">
                        <h3 class="contrib-form-title mb-1">{{ $type === 'video' ? 'Contribuer une vidéo' : 'Contribuer un audio' }}</h3>
                        @unless ($isAdmin)
                            <p class="text-muted contrib-form-lead mb-0">
                                Proposez un lien {{ $type === 'video' ? 'YouTube' : 'audio HTTPS' }}. Un administrateur validera votre contribution.
                            </p>
                        @endunless
                    </div>

                    <form action="{{ $type === 'video' ? route('contrib.videos.store') : route('contrib.audios.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" name="type" value="{{ $type === 'video' ? 1 : 0 }}">
                        <div class="d-none" aria-hidden="true">
                            <label>Ne pas remplir</label>
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <x-validation-summary />

                        <x-contrib-submitter-fields :is-admin="$isAdmin" />

                        <x-media-form-fields :type="$type" :sources="$sources" :thematiques="$thematiques" :public-contribution="true" :is-admin="$isAdmin" />

                        <div class="contrib-form-actions">
                            <x-contrib-publish-option field="statut" id="contrib-publish-media" />
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    {{ $isAdmin ? 'Enregistrer' : 'Envoyer pour validation' }}
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
