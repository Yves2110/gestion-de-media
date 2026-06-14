@extends('layouts.marketing')
@section('title', 'Espace d\'apprentissage')

@section('content')
<div class="learning-page">
    <section class="learning-hero">
        <div class="learning-hero-top">
            <p class="mb-0">Parcours de formation · Ressources pédagogiques · Bibliothèque mutualisée</p>
        </div>
        <div class="learning-hero-visual">
            <div class="learning-hero-visual-inner">
                <div class="learning-stat">
                    <span class="learning-stat-icon" aria-hidden="true"><x-feather-icon name="file-text" :size="16" /></span>
                    <span class="learning-stat-value">{{ $stats['documents'] }}</span>
                    <span class="learning-stat-label">Documents</span>
                </div>
                <div class="learning-stat">
                    <span class="learning-stat-icon" aria-hidden="true"><x-feather-icon name="video" :size="16" /></span>
                    <span class="learning-stat-value">{{ $stats['videos'] }}</span>
                    <span class="learning-stat-label">Vidéos</span>
                </div>
                <div class="learning-stat">
                    <span class="learning-stat-icon" aria-hidden="true"><x-feather-icon name="headphones" :size="16" /></span>
                    <span class="learning-stat-value">{{ $stats['audios'] }}</span>
                    <span class="learning-stat-label">Audios</span>
                </div>
            </div>
        </div>
        <div class="learning-hero-bottom">
            <div class="learning-banner-red">
                <h1 class="mb-0">Espace d'apprentissage</h1>
            </div>
            <div class="learning-banner-orange">
                <p class="mb-0">Explorez, filtrez et trouvez les ressources qui vous aideront à progresser</p>
            </div>
        </div>
    </section>

    <section class="learning-search-section">
        <div class="container">
            <form method="GET" action="{{ route('learning.index') }}" id="learningFilterForm" class="learning-filter-panel">
                <div class="learning-search-bar">
                    <span class="learning-search-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </span>
                    <input
                        type="search"
                        name="q"
                        id="learningSearchInput"
                        class="form-control learning-search-input"
                        placeholder="Titre, auteur, thème, source… (suggestions en direct)"
                        value="{{ request('q') }}"
                        autocomplete="off"
                        aria-autocomplete="list"
                        aria-controls="learningSuggestions"
                        aria-describedby="learningSearchHint"
                    >
                    <button type="submit" class="btn learning-btn-primary learning-search-submit">Rechercher</button>
                    <div id="learningSuggestions" class="learning-suggestions-dropdown" hidden role="listbox" aria-label="Suggestions de recherche"></div>
                </div>
                <p id="learningSearchHint" class="learning-search-hint mb-0">
                    <x-feather-icon name="zap" :size="14" class="me-1" />
                    Saisissez au moins 2 lettres ou cliquez dans le champ pour voir les suggestions.
                </p>

                <div class="learning-suggestions-hub" id="learningSuggestionsHub">
                    <div class="learning-suggestions-hub-head">
                        <span class="learning-suggestions-hub-icon" aria-hidden="true"><x-feather-icon name="{{ $personalized['has_history'] ? 'user' : 'compass' }}" :size="18" /></span>
                        <div>
                            <strong>{{ $personalized['has_history'] ? 'Pour vous' : 'Suggestions' }}</strong>
                            <span class="d-block text-muted small">{{ $personalized['message'] }}</span>
                        </div>
                    </div>

                    @if ($personalized['resources'] !== [])
                    <div class="learning-suggestions-hub-section">
                        <span class="learning-quick-label">{{ $personalized['has_history'] ? 'Continuer à explorer' : 'Populaire en ce moment' }}</span>
                        <ul class="learning-personalized-list">
                            @foreach ($personalized['resources'] as $resource)
                                <li>
                                    <a href="{{ $resource['url'] }}">
                                        <span class="learning-highlight-type">{{ ucfirst($resource['type']) }}</span>
                                        <span class="learning-highlight-title">{{ $resource['title'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if ($personalized['chips'] !== [])
                    <div class="learning-suggestions-hub-section">
                        <span class="learning-quick-label">Raccourcis</span>
                        <div class="learning-quick-chips">
                            @foreach ($personalized['chips'] as $chip)
                                <a href="{{ $chip['url'] }}" class="learning-chip learning-chip-theme">{{ $chip['label'] }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if (!$personalized['has_history'])
                    <div class="learning-suggestions-hub-section">
                        <span class="learning-quick-label">Parcours recommandés</span>
                        <div class="learning-quick-chips">
                            @foreach ($suggestionTopics as $topic)
                                <a href="{{ route('learning.index', array_filter(['q' => $topic['q'], 'types' => $topic['types'] ?? null])) }}" class="learning-chip learning-chip-topic">
                                    <x-feather-icon name="arrow-up-right" :size="12" />
                                    {{ $topic['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="learning-toolbar">
                    <div class="learning-type-filters">
                        @php
                            $selectedTypes = (array) request('types', []);
                            $allTypesSelected = $selectedTypes === [];
                        @endphp
                        <span class="learning-toolbar-label">Format</span>
                        <label class="learning-type-pill {{ $allTypesSelected || in_array('audio', $selectedTypes, true) ? 'active' : '' }}">
                            <input type="checkbox" name="types[]" value="audio" class="d-none learning-type-input" @checked($allTypesSelected || in_array('audio', $selectedTypes, true))>
                            <span>Audios</span>
                        </label>
                        <label class="learning-type-pill {{ $allTypesSelected || in_array('video', $selectedTypes, true) ? 'active' : '' }}">
                            <input type="checkbox" name="types[]" value="video" class="d-none learning-type-input" @checked($allTypesSelected || in_array('video', $selectedTypes, true))>
                            <span>Vidéos</span>
                        </label>
                        <label class="learning-type-pill {{ $allTypesSelected || in_array('document', $selectedTypes, true) ? 'active' : '' }}">
                            <input type="checkbox" name="types[]" value="document" class="d-none learning-type-input" @checked($allTypesSelected || in_array('document', $selectedTypes, true))>
                            <span>Documents</span>
                        </label>
                    </div>

                    <div class="learning-toolbar-actions">
                        <select name="sort" class="form-select form-select-sm learning-sort-select" aria-label="Trier les résultats">
                            <option value="recent" @selected(request('sort', 'recent') === 'recent')>Plus récents</option>
                            <option value="relevance" @selected(request('sort') === 'relevance')>Pertinence</option>
                            <option value="popular" @selected(request('sort') === 'popular')>Plus consultés</option>
                        </select>
                        <button type="button" class="btn btn-sm learning-btn-outline learning-advanced-toggle" aria-expanded="{{ $activeFilterCount > 0 ? 'true' : 'false' }}" aria-controls="learningAdvancedFilters">
                            Filtres avancés
                            @if ($activeFilterCount > 0)
                                <span class="learning-filter-badge">{{ $activeFilterCount }}</span>
                            @endif
                        </button>
                    </div>
                </div>

                <div id="learningAdvancedFilters" class="learning-advanced-filters" @if($activeFilterCount === 0) hidden @endif>
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <label class="learning-filter-label">Sources</label>
                            <div class="learning-filter-chips">
                                @forelse ($filters['sources'] as $source)
                                    <label class="learning-filter-chip">
                                        <input type="checkbox" name="source_id[]" value="{{ $source->id }}" @checked(in_array((string) $source->id, array_map('strval', (array) request('source_id', [])), true))>
                                        <span>{{ $source->label }}</span>
                                    </label>
                                @empty
                                    <span class="text-muted small">Aucune source disponible.</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label class="learning-filter-label">Thématiques</label>
                            <div class="learning-filter-chips">
                                @forelse ($filters['thematiques'] as $thematique)
                                    <label class="learning-filter-chip">
                                        <input type="checkbox" name="thematique_id[]" value="{{ $thematique->id }}" @checked(in_array((string) $thematique->id, array_map('strval', (array) request('thematique_id', [])), true))>
                                        <span>{{ $thematique->label }}</span>
                                    </label>
                                @empty
                                    <span class="text-muted small">Aucune thématique disponible.</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label class="learning-filter-label">Catégories (documents)</label>
                            <div class="learning-filter-chips">
                                @forelse ($filters['categories'] as $category)
                                    <label class="learning-filter-chip">
                                        <input type="checkbox" name="category_id[]" value="{{ $category->id }}" @checked(in_array((string) $category->id, array_map('strval', (array) request('category_id', [])), true))>
                                        <span>{{ $category->label }}</span>
                                    </label>
                                @empty
                                    <span class="text-muted small">Aucune catégorie disponible.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="learning-advanced-actions">
                        <button type="submit" class="btn btn-sm learning-btn-primary">Appliquer</button>
                        <a href="{{ route('learning.index') }}" class="btn btn-sm learning-btn-ghost">Tout effacer</a>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="learning-results">
        <div class="container">
            @if (!$hasActiveSearch && $stats['total'] > 0)
                <div class="learning-highlights row g-3 mb-4">
                    @if ($curated['recent'] !== [])
                    <div class="col-lg-6">
                        <div class="learning-highlight-card">
                            <div class="learning-highlight-head">
                                <h2>Nouveautés</h2>
                                <span class="learning-highlight-tag">Récent</span>
                            </div>
                            <ul class="learning-highlight-list">
                                @foreach (collect($curated['recent'])->take(3) as $resource)
                                    <li>
                                        <a href="{{ $resource['url'] }}">
                                            <span class="learning-highlight-type">{{ ucfirst($resource['type']) }}</span>
                                            <span class="learning-highlight-title">{{ $resource['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                    @if ($curated['popular'] !== [])
                    <div class="col-lg-6">
                        <div class="learning-highlight-card">
                            <div class="learning-highlight-head">
                                <h2>Tendances</h2>
                                <span class="learning-highlight-tag learning-highlight-tag-alt">Populaire</span>
                            </div>
                            <ul class="learning-highlight-list">
                                @foreach (collect($curated['popular'])->take(3) as $resource)
                                    <li>
                                        <a href="{{ $resource['url'] }}">
                                            <span class="learning-highlight-type">{{ ucfirst($resource['type']) }}</span>
                                            <span class="learning-highlight-title">{{ $resource['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>

                @if ($curated['featured_thematiques']->isNotEmpty())
                <div class="learning-thematiques-strip mb-4">
                    <span class="learning-thematiques-label">Thématiques</span>
                    <div class="learning-quick-chips">
                        @foreach ($curated['featured_thematiques'] as $thematique)
                            <a href="{{ route('learning.index', ['thematique_id' => [$thematique->id]]) }}" class="learning-chip learning-chip-theme">{{ $thematique->label }}</a>
                        @endforeach
                    </div>
                </div>
                @endif
            @endif

            <div class="learning-results-head">
                <h2 class="portal-section-title mb-0">
                    @if ($hasActiveSearch)
                        {{ $results->total() }} ressource{{ $results->total() > 1 ? 's' : '' }} trouvée{{ $results->total() > 1 ? 's' : '' }}
                    @else
                        Toutes les ressources
                    @endif
                </h2>
                @if ($stats['total'] > 0)
                    <span class="badge portal-count-badge">{{ $results->total() }} disponible(s)</span>
                @endif
            </div>

            @if ($results->isEmpty())
                <div class="learning-empty-state">
                    <div class="learning-empty-icon" aria-hidden="true">
                        <x-feather-icon name="inbox" :size="40" />
                    </div>
                    <h3>Aucune ressource pour le moment</h3>
                    @if ($hasActiveSearch)
                        <p class="text-muted">Élargissez votre recherche ou retirez quelques filtres.</p>
                        <a href="{{ route('learning.index') }}" class="btn learning-btn-primary btn-sm mt-2">Voir toutes les ressources</a>
                    @else
                        <p class="text-muted">Les contenus publiés par les administrateurs apparaîtront ici.</p>
                    @endif
                </div>
            @else
                <div class="row learning-grid">
                    @foreach ($results as $resource)
                        @include('learning.partials.resource-card', ['resource' => $resource])
                    @endforeach
                </div>

                @if ($results->hasPages())
                    <div class="d-flex justify-content-center mt-4 portal-pagination">
                        {{ $results->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    window.learningSpaceConfig = {
        suggestionsUrl: @json(route('learning.suggestions')),
    };
</script>
<script src="{{ asset('assets/js/learning-space.js') }}"></script>
@endpush
