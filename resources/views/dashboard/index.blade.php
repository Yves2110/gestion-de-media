@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            @include('components.flash-messages')

            @if ($showOnboarding ?? false)
                @include('dashboard.components.onboarding-checklist')
            @endif

            <div class="row match-height mb-2">
                <div class="col-12">
                    <h3>Bienvenue, {{ Auth::user()->firstname }} !</h3>
                    <p class="text-muted">Vue d'ensemble de votre plateforme média.</p>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar bg-light-primary me-2 p-50"><i data-feather="volume-2"></i></div>
                            <div>
                                <h4 class="fw-bolder mb-0">{{ $stats['audios_published'] }}</h4>
                                <small>Audios publiés ({{ $stats['audios_draft'] }} brouillons)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar bg-light-info me-2 p-50"><i data-feather="video"></i></div>
                            <div>
                                <h4 class="fw-bolder mb-0">{{ $stats['videos_published'] }}</h4>
                                <small>Vidéos publiées ({{ $stats['videos_draft'] }} brouillons)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar bg-light-success me-2 p-50"><i data-feather="file-text"></i></div>
                            <div>
                                <h4 class="fw-bolder mb-0">{{ $stats['documents_published'] }}</h4>
                                <small>Documents ({{ $stats['documents_draft'] }} brouillons)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar bg-light-warning me-2 p-50"><i data-feather="users"></i></div>
                            <div>
                                <h4 class="fw-bolder mb-0">{{ $stats['active_users'] }}</h4>
                                <small>Utilisateurs ({{ $stats['clients'] }} clients)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (isset($viewsThisMonth))
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="text-muted">Vues ce mois</h6>
                            <h3 class="mb-0">{{ $viewsThisMonth }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Actions rapides</h5></div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <a href="{{ route('audios.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Audio</a>
                    <a href="{{ route('videos.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Vidéo</a>
                    <a href="{{ route('documents.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Document</a>
                    <a href="{{ route('thematique.index') }}" class="btn btn-outline-primary">Thématiques</a>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header"><h5 class="mb-0">Activité récente</h5></div>
                        <div class="card-body">
                            @forelse ($recentActivity as $activity)
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <div>
                                        <span class="badge bg-light-secondary">{{ $activity['type'] }}</span>
                                        <a href="{{ $activity['edit_route'] }}">{{ $activity['title'] }}</a>
                                        <small class="text-muted d-block">{{ $activity['user'] ?? 'Utilisateur inconnu' }} | {{ $activity['date']->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @empty
                                @include('components.empty-state', [
                                    'title' => 'Aucune activité',
                                    'message' => 'Commencez par ajouter un contenu.',
                                    'actionUrl' => route('audios.create'),
                                    'actionLabel' => 'Ajouter un audio',
                                ])
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header"><h5 class="mb-0">Brouillons à publier</h5></div>
                        <div class="card-body">
                            @forelse ($draftsToPublish as $draft)
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <div>
                                        <span class="badge bg-warning">{{ $draft['type'] }}</span>
                                        {{ $draft['title'] }}
                                    </div>
                                    <a href="{{ $draft['edit_route'] }}" class="btn btn-sm btn-outline-primary">Publier</a>
                                </div>
                            @empty
                                <p class="text-muted mb-0">Aucun brouillon en attente.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Publications (4 dernières semaines)</h5></div>
                <div class="card-body">
                    <div class="d-flex align-items-end gap-2" style="height: 120px;">
                        @foreach ($weeklyPublications as $week)
                            <div class="text-center flex-fill">
                                <div class="bg-primary rounded mx-auto mb-1" style="height: {{ max(8, $week['count'] * 20) }}px; max-height: 100px; width: 40px;"></div>
                                <small>{{ $week['label'] }} ({{ $week['count'] }})</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if (isset($topContent) && $topContent->isNotEmpty())
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Top contenus (7 jours)</h5></div>
                <ul class="list-group list-group-flush">
                    @foreach ($topContent as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $item->title ?? $item->viewable_title }}</span>
                            <span class="badge bg-primary">{{ $item->views_count }} vues</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
@include('dashboard.components.footer')
@endsection
