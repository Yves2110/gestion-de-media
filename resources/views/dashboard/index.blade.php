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

            <div class="row align-items-center dashboard-hero mb-2 g-2">
                <div class="col-md-8 col-lg-9">
                    <h3 class="mb-1">Bienvenue, {{ Auth::user()->firstname }} !</h3>
                    <p class="text-muted mb-0">Vue d'ensemble de votre plateforme média.</p>
                </div>
                @if (isset($viewsThisMonth))
                <div class="col-md-4 col-lg-3">
                    <div class="card dashboard-views-card border-primary mb-0 h-100">
                        <div class="card-body py-2 px-3 d-flex flex-column justify-content-center text-md-end">
                            <span class="text-muted small">Vues ce mois</span>
                            <span class="dashboard-views-count">{{ $viewsThisMonth }}</span>
                        </div>
                    </div>
                </div>
                @endif
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
                    <div class="card h-100 dashboard-list-card">
                        <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <h5 class="mb-0">Activité récente</h5>
                            @if ($recentActivity->total() > 0)
                                <small class="text-muted">{{ $recentActivity->total() }} au total</small>
                            @endif
                        </div>
                        <div class="card-body p-0">
                            @forelse ($recentActivity as $activity)
                                <div class="dashboard-list-item">
                                    <div>
                                        <span class="badge bg-light-secondary">{{ $activity['type'] }}</span>
                                        <a href="{{ $activity['edit_route'] }}" class="dashboard-list-title">{{ $activity['title'] }}</a>
                                        <small class="text-muted d-block">{{ $activity['user'] ?? 'Utilisateur inconnu' }} · {{ $activity['date']->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="p-3">
                                    @include('components.empty-state', [
                                        'title' => 'Aucune activité',
                                        'message' => 'Commencez par ajouter un contenu.',
                                        'actionUrl' => route('audios.create'),
                                        'actionLabel' => 'Ajouter un audio',
                                    ])
                                </div>
                            @endforelse
                        </div>
                        @if ($recentActivity->hasPages())
                            <div class="card-footer py-2 dashboard-mini-pagination">
                                {{ $recentActivity->links() }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100 dashboard-list-card">
                        <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <h5 class="mb-0">Brouillons à publier</h5>
                            @if ($draftsToPublish->total() > 0)
                                <small class="text-muted">{{ $draftsToPublish->total() }} en attente</small>
                            @endif
                        </div>
                        <div class="card-body p-0">
                            @forelse ($draftsToPublish as $draft)
                                <div class="dashboard-list-item d-flex justify-content-between align-items-center gap-2">
                                    <div class="min-w-0">
                                        <span class="badge bg-warning">{{ $draft['type'] }}</span>
                                        <span class="dashboard-list-title d-block text-truncate">{{ $draft['title'] }}</span>
                                    </div>
                                    <a href="{{ $draft['edit_route'] }}" class="btn btn-sm btn-outline-primary flex-shrink-0">Publier</a>
                                </div>
                            @empty
                                <p class="text-muted mb-0 p-3">Aucun brouillon en attente.</p>
                            @endforelse
                        </div>
                        @if ($draftsToPublish->hasPages())
                            <div class="card-footer py-2 dashboard-mini-pagination">
                                {{ $draftsToPublish->links() }}
                            </div>
                        @endif
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
