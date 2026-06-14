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
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-xl-7">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-center mb-1">Ajouter une vidéo</h5>
                                    <p class="text-muted text-center small mb-4">Renseignez les informations et le lien YouTube du contenu.</p>
                                    <form action="{{ route('videos.store') }}" method="post" enctype="multipart/form-data" novalidate>
                                        @csrf
                                        <x-media-form-fields type="video" :sources="$sources" :thematiques="$thematiques" />
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
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
