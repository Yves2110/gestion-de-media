@extends('layouts.client')
@section('title', 'Vidéos')

@section('content')
<div class="row">
    <div class="col-md-3">@include('client.partials.filters')</div>
    <div class="col-md-9">
        <h4 class="mb-3">Catalogue vidéo</h4>
        <div class="row">
            @forelse ($videos as $video)
                @include('client.partials.media-card', ['item' => $video, 'type' => 'video'])
            @empty
                <div class="col-12">
                    @include('components.empty-state', [
                        'title' => 'Aucune vidéo disponible',
                        'message' => 'Essayez de modifier vos filtres.',
                    ])
                </div>
            @endforelse
        </div>
        {{ $videos->withQueryString()->links() }}
    </div>
</div>
@endsection
