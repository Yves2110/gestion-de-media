@extends('layouts.dashboard')
@section('dashboard_content')
    @include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')
    <div class="app-content content admin-main-content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <section id="dashboard-ecommerce">
                    <div class="content-body">
                        <div class="row" id="basic-table">
                            <div class="col-6">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Catégorie</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @forelse ($categories as $category)
                                                <tr>
                                                    <td class="text-center">{{ $category->label }}</td>
                                                    <td class="d-flex float-end">
                                                        <a href="{{ route('category.edit', $category->id) }}">
                                                            <button type="button" class="btn btn-success">Editer</button>
                                                        </a>
                                                        <form action="{{ route('category.destroy', $category->id) }}" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger mx-2">Supprimer</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2">
                                                        @include('components.empty-state', [
                                                            'title' => 'Aucune catégorie',
                                                            'message' => 'Créez une catégorie pour classer vos documents.',
                                                        ])
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 card">
                                <div class="card-body">
                                    @if ($message = Session::get('message'))
                                        <div class="alert alert-success mt-1 alert-dismissible" role="alert">
                                            <div class="alert-body d-flex align-items-center">
                                                <span>{{ $message }}</span>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <h6 class="text-uppercase text-center">Formulaire d'ajout d'une catégorie</h6>
                                    <form action="{{ route('category.store') }}" method="post">
                                        @csrf
                                        <label class="form-label">Catégorie</label>
                                        <input type="text" name="label" placeholder="Ex. Rapport, Guide..." class="form-control" value="{{ old('label') }}">
                                        @error('label')
                                            <h6 class="fw-bold text-danger">{{ $message }}</h6>
                                        @enderror
                                        <button type="submit" class="btn btn-primary mt-2 float-end">Valider</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
        </div>
    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    </div>
@include('dashboard.components.footer')
@endsection
