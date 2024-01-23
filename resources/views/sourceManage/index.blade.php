@extends('layouts.dashboard')
@section('dashboard_content')
    @include('dashboard.components.nav')
    @include('dashboard.components.menu');
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section id="dashboard-ecommerce">
                    <div class="content-body">
                        <!-- Basic Tables start -->
                        <div class="row" id="basic-table">
                            <div class="col-6">
                                <div class="card">
                                    <div class="table-responsive">
                                        {{-- <button class="btn btn-primary float-end m-1"><a href="{{ route('source.create') }}"
                                                class="text-white">Ajouter une source</a></button> --}}
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Source</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            @foreach ($sources as $source)
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                            {{ $source->label }}
                                                        </td>
                                                        <td class="d-flex float-end">
                                                            <a href="{{ route('source.edit', $source->id) }}">
                                                                <button type="submit" class="btn btn-success">
                                                                    Editer
                                                                </button>
                                                            </a>
                                                            <form action="{{ route('source.destroy', $source->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger mx-2">
                                                                    Supprimer
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6  card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        @if ($message = Session::get('message'))
                                            <div class="alert alert-success mt-1 alert-dismissible" role="alert">
                                                <div class="alert-body d-flex align-items-center">
                                                    <span>{{ $message }}</span>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                        @endif
                                        <h6 class="text-uppercase text-center">Formulaire d'ajout d'une source</h6>
                                        <form action="{{ route('source.store') }}" method="post">
                                            @csrf
                                            <div class="">
                                                <label class="form-label">Source</label>
                                                <input type="text" name="label" placeholder="Saisir une source" class="form-control ">
                                                @error('label')
                                                <h6 class="fw-bold  text-danger">{{ $message }}
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-2 float-end">Valider</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Basic Tables end -->

                </section>


            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    @include('dashboard.components.footer')
    </body>
@endsection
