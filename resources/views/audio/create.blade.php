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
                            <div class="col-6 offset-3">
                                <div class="card">

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            @if ($message = Session::get('message'))
                                                <div class="alert alert-danger mt-1 alert-dismissible" role="alert">
                                                    <div class="alert-body d-flex align-items-center">
                                                        <span>{{ $message }}</span>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            @endif
                                            <h6 class="text-uppercase text-center">Formulaire d'ajout d'un audio</h6>
                                            <form action="{{ route('audio.store') }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-2">
                                                    <label class="form-label">Titre</label>
                                                    <input type="text" name="title" class="form-control ">
                                                    @error('titre')
                                                    <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                                    @enderror
                                                    <label class="form-label">Auteur</label>
                                                    <input type="text" name="auteur" class="form-control ">
                                                    @error('auteur')
                                                    <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                                    @enderror
                                                    <label class="form-label">Code Média</label>
                                                    <input type="text" name="code_media" class="form-control ">
                                                    @error('code_media')
                                                    <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                                    @enderror
                                                    <select class="form-select my-2" name="source_id" aria-label="Default select example">
                                                        <option selected>Selectionner une source</option>
                                                        @forelse ($sources as $source)
                                                        <option value="{{$source->id}}">{{$source->label}}</option>
                                                        @empty
                                                            <p>Aucune source</p>
                                                        @endforelse
                                                      </select>
                                                      <select class="form-select" name="thematique_id[]" multiple aria-label="multiple select example">
                                                        <option selected>Open this select menu</option>
                                                        @foreach ($thematiques as $thematique)
                                                        <option value="{{$thematique->id}}">{{$thematique->label}}</option>
                                                        @endforeach
                                                      </select>


                                                      <div class="my-2">
                                                        <label for="formFile" class="form-label">Fichier audio</label>
                                                        <input class="form-control" name="audio" type="file" id="formFile">
                                                      </div>

                                                      <div class="form-floating my-1">
                                                        <textarea class="form-control" name="description" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                                                        <label for="floatingTextarea2">Description</label>
                                                      </div>

                                                      <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="statut" value="0" id="flexCheckDefault">
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                          Demande de publication
                                                        </label>
                                                      </div>
                                                     
                                                    <button type="submit" class="btn btn-primary mt-2">Valider</button>
                                                </div>
                                            </form>
                                        </div>
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
    @include('dashboard.components.footer')
    </body>
@endsection
