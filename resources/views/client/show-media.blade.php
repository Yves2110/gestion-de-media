@extends('layouts.client')
@section('title', $item->title)

@section('content')
<div class="card">
    <div class="card-body">
        <h3>{{ $item->title }}</h3>
        <p><strong>Auteur :</strong> {{ $item->auteur }}</p>
        <p><strong>Source :</strong> {{ $item->source->label ?? '-' }}</p>
        @if ($item->description)
            <p>{{ $item->description }}</p>
        @endif
        @if ($type === 'video')
            <div class="my-3">
                <x-video-player :content="$item->media" />
            </div>
        @else
            <div class="my-3 p-3 bg-light rounded"><x-safe-media :content="$item->media" /></div>
        @endif
        @if ($item->localisation)
            <hr>
            <h5>Localisation</h5>
            <x-safe-localisation :content="$item->localisation" />
        @endif
        <a href="{{ route('catalogue.' . ($type === 'audio' ? 'audios' : 'videos')) }}" class="btn btn-secondary mt-3">Retour au catalogue</a>
    </div>
</div>
@endsection
