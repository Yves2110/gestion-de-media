@extends('layouts.marketing')
@section('title', 'Accueil')

@section('content')
<section class="portal-hero">
    <div class="portal-hero-top">
        <p class="mb-0">Plateforme de mutualisation et de partage de connaissances</p>
    </div>

    <div class="portal-hero-collage">
        @foreach ($heroImages as $index => $image)
            <div class="portal-hero-panel panel-{{ $index + 1 }}">
                <img src="{{ $image }}" alt="Illustration {{ $index + 1 }}" loading="lazy"
                     data-fallback="{{ asset('assets/images/hero/hero-' . ($index + 1) . '.svg') }}"
                     onerror="if(this.dataset.fallback){this.src=this.dataset.fallback;}">
            </div>
        @endforeach
    </div>

    <div class="portal-hero-bottom">
        <div class="portal-banner-red">
            <h1 class="mb-0">Gestion Media | Bibliothèque numérique</h1>
        </div>
        <div class="portal-banner-orange">
            <p class="mb-0">Centralisez, publiez et diffusez vos audios, vidéos et documents</p>
        </div>
    </div>
</section>

<section id="publications" class="portal-publications py-5">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h2 class="portal-section-title mb-0">{{ $sectionTitle }}</h2>
            @if ($sectionCount > 0)
                <span class="badge portal-count-badge">{{ $sectionCount }} publié(s)</span>
            @endif
        </div>

        <div class="portal-type-filters mb-4">
            <a href="{{ route('home') }}#publications" class="portal-type-filter {{ !$typeFilter ? 'active' : '' }}">Tout</a>
            @if ($stats['documents'] > 0)
                <a href="{{ route('home', ['type' => 'documents']) }}#publications" class="portal-type-filter {{ $typeFilter === 'documents' ? 'active' : '' }}">Documents</a>
            @endif
            @if ($stats['videos'] > 0)
                <a href="{{ route('home', ['type' => 'videos']) }}#publications" class="portal-type-filter {{ $typeFilter === 'videos' ? 'active' : '' }}">Vidéos</a>
            @endif
            @if ($stats['audios'] > 0)
                <a href="{{ route('home', ['type' => 'audios']) }}#publications" class="portal-type-filter {{ $typeFilter === 'audios' ? 'active' : '' }}">Audios</a>
            @endif
        </div>

        @if ($publications->isEmpty())
            <div class="text-center py-5 bg-white rounded shadow-sm">
                @include('components.empty-state', [
                    'title' => 'Aucune publication pour le moment',
                    'message' => 'Les contenus publiés par les administrateurs apparaîtront ici.',
                    'actionUrl' => auth()->check() && in_array(auth()->user()->role_id, [1, 2], true) ? route('audios.create') : route('login'),
                    'actionLabel' => auth()->check() ? 'Ajouter un contenu' : 'Se connecter',
                ])
            </div>
        @else
            <div class="row">
                @foreach ($publications as $publication)
                    @if ($publication['type'] === 'document')
                        @include('client.partials.document-card', ['document' => $publication['item']])
                    @else
                        @include('home.partials.publication-card', ['publication' => $publication])
                    @endif
                @endforeach
            </div>
            @if ($publications->hasPages())
                <div class="d-flex justify-content-center mt-4 portal-pagination">
                    {{ $publications->links() }}
                </div>
            @endif
        @endif
    </div>
</section>

@guest
<section class="portal-cta py-4">
    <div class="container text-center">
        <p class="mb-3 text-white">Parcourez la bibliothèque publique, ou contribuez sans compte.</p>
        <a href="{{ route('login') }}" class="btn btn-light me-2">Se connecter</a>
        <a href="{{ route('register') }}" class="btn btn-outline-light me-2">Créer un compte</a>
        <x-contrib-menu align="cta" />
    </div>
</section>
@endguest
@endsection
