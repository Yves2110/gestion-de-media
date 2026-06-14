@extends('layouts.dashboard')
@section('title', 'Audios')
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Gestion des audios</h4>
                    <div>
                        <a href="{{ route('audios.index', ['status' => 'published']) }}" class="btn btn-sm btn-outline-success">Publiés</a>
                        <a href="{{ route('audios.index', ['status' => 'draft']) }}" class="btn btn-sm btn-outline-warning">Brouillons</a>
                        <a href="{{ route('audios.index') }}" class="btn btn-sm btn-outline-secondary">Tous</a>
                        <a href="{{ route('audios.create') }}" class="btn btn-primary">Ajouter un audio</a>
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
                                    <th>Code</th>
                                    <th>Source</th>
                                    <th>Thématique</th>
                                    <th>Statut</th>
                                    <th>Enregistré par</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($audios as $audio)
                                <tr>
                                    <td>
                                        @if ($audio->picture)
                                            <img src="{{ asset('storage/picture/' . $audio->picture) }}" width="50" height="50" class="rounded object-fit-cover" alt="couverture">
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $audio->title }}</td>
                                    <td>{{ $audio->auteur }}</td>
                                    <td>{{ $audio->code_media ?? 'Non attribué' }}</td>
                                    <td>{{ $audio->source->label ?? '-' }}</td>
                                    <td>
                                        @foreach ($audio->custom as $thematique)
                                            <span class="badge bg-light-primary">{{ $thematique->label }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($audio->statut)
                                            <span class="badge bg-success">Publié</span>
                                        @else
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                    </td>
                                    <td>{{ $audio->user->firstname }} {{ $audio->user->lastname }}</td>
                                    <td class="text-end text-nowrap">
                                        <x-admin-media-actions :item="$audio" type="audio" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">
                                        @include('components.empty-state', [
                                            'title' => 'Aucun audio',
                                            'message' => 'Commencez par ajouter votre premier contenu audio.',
                                            'actionUrl' => route('audios.create'),
                                            'actionLabel' => 'Ajouter un audio',
                                        ])
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $audios->links() }}
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
