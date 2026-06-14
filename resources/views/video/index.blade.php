@extends('layouts.dashboard')
@section('title', 'Vidéos')
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Gestion des vidéos</h4>
                    <div>
                        <a href="{{ route('videos.index', ['status' => 'published']) }}" class="btn btn-sm btn-outline-success">Publiées</a>
                        <a href="{{ route('videos.index', ['status' => 'draft']) }}" class="btn btn-sm btn-outline-warning">Brouillons</a>
                        <a href="{{ route('videos.index') }}" class="btn btn-sm btn-outline-secondary">Toutes</a>
                        <a href="{{ route('videos.create') }}" class="btn btn-primary">Ajouter une vidéo</a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('message'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Couverture</th>
                                    <th>Titre</th>
                                    <th>Auteur</th>
                                    <th>Code vidéo</th>
                                    <th>Source</th>
                                    <th>Thématique</th>
                                    <th>Statut</th>
                                    <th>Enregistré par</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($videos as $video)
                                <tr>
                                    <td>
                                        @if ($video->picture)
                                            <img src="{{ asset('storage/picture/' . $video->picture) }}" width="50" height="50" class="rounded object-fit-cover" alt="couverture">
                                        @elseif ($video->thumbnail_url)
                                            <img src="{{ $video->thumbnail_url }}" width="50" height="50" class="rounded object-fit-cover" alt="miniature YouTube">
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $video->title }}</td>
                                    <td>{{ $video->auteur }}</td>
                                    <td>{{ $video->code_media ?? 'Non attribué' }}</td>
                                    <td>{{ $video->source->label ?? '-' }}</td>
                                    <td>
                                        @foreach ($video->custom as $thematique)
                                            <span class="badge bg-light-primary">{{ $thematique->label }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($video->statut)
                                            <span class="badge bg-success">Publiée</span>
                                        @else
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                    </td>
                                    <td>{{ $video->user->firstname }} {{ $video->user->lastname }}</td>
                                    <td class="text-end text-nowrap">
                                        <x-admin-media-actions :item="$video" type="video" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">
                                        @include('components.empty-state', [
                                            'title' => 'Aucune vidéo',
                                            'message' => 'Ajoutez votre première vidéo au catalogue.',
                                            'actionUrl' => route('videos.create'),
                                            'actionLabel' => 'Ajouter une vidéo',
                                        ])
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $videos->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('dashboard.components.footer')
@push('scripts')
<script>if (typeof feather !== 'undefined') feather.replace({ width: 16, height: 16 });</script>
@endpush
@endsection
