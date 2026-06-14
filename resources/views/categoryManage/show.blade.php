@extends('layouts.dashboard')
@section('dashboard_content')
    @include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')
    <div class="app-content content admin-main-content">
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <div class="card">
                    <div class="card-body">
                        <h5>{{ $category->label }}</h5>
                        <p class="text-muted mb-0">{{ $category->documents()->count() }} document(s) associé(s)</p>
                        <a href="{{ route('category.index') }}" class="btn btn-outline-secondary btn-sm mt-3">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@include('dashboard.components.footer')
@endsection
