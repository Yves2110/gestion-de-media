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
                                            <form action="{{ route('audios.store') }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-2">
                                                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                                    <input type="hidden" name="type" value="0">
                                                    <label class="form-label">Titre</label>
                                                    <input type="text" name="title" placeholder="Saisir un titre" class="form-control mb-1 ">
                                                    @error('title')
                                                    <h6 class="fw-bold text-danger">{{ $message }}</h6>
                                                    @enderror
                                                    <label class="form-label">Auteur</label>
                                                    <input type="text" name="auteur" placeholder="Saisir le nom de l'auteur" class="form-control ">
                                                    @error('auteur')
                                                    <h6 class="fw-bold mt-1 text-danger">{{ $message }}</h6>
                                                    @enderror
                                                    <label class="form-label">Code Média</label>
                                                    <input type="text" name="code_media"  placeholder="Saisir un code Media" class="form-control ">

                                                    <select class="form-select my-2"  name="source_id"  aria-label="Default select example" required>
                                                        <option value=""  >Selectionner une source</option>
                                                        @foreach ($sources as $source)
                                                        <option value="{{$source->id}}" >{{$source->label}}</option>
                                                        @endforeach
                                                      </select>
                                                      
                                                      <label class="form-label" for="basicSelect">Sélection une thématique</label> 
                                                      <select class="form-select" name="thematique_id[]" multiple aria-label="multiple select example" required>
                                                        @foreach ($thematiques as $thematique)
                                                        <option value="{{$thematique->id}}">{{$thematique->label}}</option>
                                                        @endforeach
                                                      </select>
                                                      <h6 class="text-warning fw-bold">Maintenir ctrl + clic droit de la souris pour selectionner plusieurs thémaques</h6>


                                                      <div class="my-2">
                                                        <label for="formFile" class="form-label">Lien audio</label>
                                                        <input class="form-control" name="media"  type="text" id="formFile">
                                                        @error('media')
                                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}</h6>
                                                        @enderror
                                                      </div>

                                                      <div class="form-floating my-1">
                                                        <textarea class="form-control" name="description" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                                                        <label for="floatingTextarea2">Description</label>
                                                      </div>

                                                      <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="statut">
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                          Demande de publication
                                                        </label>
                                                      </div>
                                                     
                                                    <button type="submit" class="btn btn-primary mt-2 float-end">Valider</button>
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
@endsection
