@extends('layouts.client')
@section('title', 'Documents')

@section('content')
<div class="row">
    <div class="col-md-3">@include('client.partials.filters')</div>
    <div class="col-md-9">
        <h4 class="mb-3">Catalogue documents</h4>
        <div class="row">
            @forelse ($documents as $document)
                @include('client.partials.document-card', ['document' => $document])
            @empty
                <div class="col-12">
                    @include('components.empty-state', [
                        'title' => 'Aucun document disponible',
                        'message' => 'Essayez de modifier vos filtres.',
                    ])
                </div>
            @endforelse
        </div>
        {{ $documents->withQueryString()->links() }}
    </div>
</div>
@endsection
