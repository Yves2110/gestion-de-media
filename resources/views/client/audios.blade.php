@extends('layouts.client')
@section('title', 'Audios')

@section('content')
<div class="row">
    <div class="col-md-3">@include('client.partials.filters')</div>
    <div class="col-md-9">
        <h4 class="mb-3">Catalogue audio</h4>
        <div class="row">
            @forelse ($audios as $audio)
                @include('client.partials.media-card', ['item' => $audio, 'type' => 'audio'])
            @empty
                <div class="col-12">
                    @include('components.empty-state', [
                        'title' => 'Aucun audio disponible',
                        'message' => 'Essayez de modifier vos filtres.',
                        'actionUrl' => route('catalogue.audios'),
                        'actionLabel' => 'Réinitialiser',
                    ])
                </div>
            @endforelse
        </div>
        {{ $audios->withQueryString()->links() }}
    </div>
</div>
@endsection
