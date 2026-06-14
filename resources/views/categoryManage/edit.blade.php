@extends('layouts.dashboard')
@section('dashboard_content')
    @include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')
    <div class="app-content content admin-main-content">
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <div class="row">
                    <div class="col-4 offset-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-uppercase text-center">Modifier la catégorie</h6>
                                <form action="{{ route('category.update', $category->id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <label class="form-label">Catégorie</label>
                                    <input type="text" name="label" value="{{ old('label', $category->label) }}" class="form-control">
                                    @error('label')
                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}</h6>
                                    @enderror
                                    <button type="submit" class="btn btn-primary mt-2 float-end">Valider</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@include('dashboard.components.footer')
@endsection
