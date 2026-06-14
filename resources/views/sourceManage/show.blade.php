@extends('layouts.dashboard')
@section('title', 'Source | ' . $source->label)
@section('dashboard_content')
@include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')

<div class="app-content content admin-main-content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            <div class="card col-md-6 offset-md-3">
                <div class="card-body">
                    <h4>{{ $source->label }}</h4>
                    <p class="text-muted">Créée le {{ $source->created_at->format('d/m/Y') }}</p>
                    <a href="{{ route('source.edit', $source) }}" class="btn btn-primary">Éditer</a>
                    <a href="{{ route('source.index') }}" class="btn btn-secondary">Retour</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('dashboard.components.footer')
@endsection
