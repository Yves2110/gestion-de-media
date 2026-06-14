@extends('layouts.dashboard')
@section('dashboard_content')
    @include('dashboard.components.nav')
<div class="admin-layout-wrapper">
@include('dashboard.components.sidebar')
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content admin-main-content">
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
                            <div class="col-10 offset-1">
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
                                            <h6 class="text-uppercase text-center mb-2">Formulaire de modification d'un document</h6>
                                            <div class="alert alert-light border small mb-3">
                                                Champs affichés sur la carte : <strong>couverture, titre, auteur, source, résumé</strong>.
                                                Le PDF alimente le bouton Télécharger ; la page Lire est publique si publié.
                                            </div>
                                            <form action="{{ route('documents.update', $document) }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-2 row">
                                                    <div class="col-md-6">
                                                        <input type="hidden" name="document_id" {{$document->id}}>
                                                        <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                                        <label class="form-label">Titre</label>
                                                        <input type="text" name="title" value="{{$document->title}}" class="form-control ">
                                                        @error('title')
                                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }} </h6>
                                                        @enderror
                                                        <label class="form-label">Auteur</label>
                                                        <input type="text" name="auteur" value="{{$document->auteur}}" class="form-control ">
                                                        @error('auteur')
                                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }} </h6>
                                                        @enderror
                                                        <label class="form-label">Edition</label>
                                                        <input type="text" name="edition" value="{{$document->edition}}" class="form-control ">
                                                        @error('edition')
                                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }} </h6>
                                                        @enderror

                                                        <label class="form-label">Date de Publication</label>
                                                        <input type="date" value="{{$document->publication_date}}"  name="publication_date" class="form-control ">
                                                        @error('publication_date')
                                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }} </h6>
                                                        @enderror

                                                        <label class="form-label mt2">Catégorie</label>
                                                        <select class="form-select" name="category_id" required>
                                                            <option value="">Choisir une catégorie</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}" @selected(old('category_id', $document->category_id) == $category->id)>{{ $category->label }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('category_id')
                                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }} </h6>
                                                        @enderror

                                                        <label class="form-label mt2">Code document</label>
                                                        <input type="text" value="{{$document->code_document}}"  name="code_document" class="form-control ">
                                                        @error('code_document')
                                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }} </h6>
                                                        @enderror
                                                        
                                                        <div class="mt-2">
                                                            <label for="formFile" class="form-label">Fichier document</label>
                                                            <input class="form-control my-1"  name="file_doc" type="file" id="formFile">
                                                            <p>Document : {{ $document->file_doc }}</p>
                                                            @error('file_doc')
                                                            <h6 class="fw-bold mt-1 text-danger">{{ $message }} </h6>
                                                            @enderror
                                                          </div>

                                                          <div class="my-2">
                                                            <label for="formFile" class="form-label">Image du document</label>
                                                            <input class="form-control my-1"  name="picture" type="file" id="formFile">
                                                            <img src="{{ asset('storage/picture/' . $document->picture) }}" width="75px" height="75px">
                                                            @error('picture')
                                                            <h6 class="fw-bold mt-1 text-danger">{{ $message }} </h6>
                                                            @enderror
                                                          </div>
                                                    </div>

                                                        {{-- Deuxieme section du formulaire --}}

                                                    <div class="col-md-6">

                                                        <label class="form-label ">Nombre de Page</label>
                                                        <input type="integer" value="{{$document->page}}"  name="page" class="form-control ">
                                                        @error('pagenumber')
                                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                                        @enderror

                                                        <label class="form-label" for="basicSelect">Sélection une source</label> 
                                                        <select class="form-select" name="source_id" aria-label="Default select example">
                                                            <option selected >Selectionner une source</option>
                                                            @forelse ($sources as $source)
                                                            <option value="{{ $source->id }}"
                                                                {{ $document->source_id == $source->id ? 'selected' : '' }}>
                                                                {{ $source->label }}
                                                            </option>
                                                            @empty
                                                                <p>Aucune source</p>
                                                            @endforelse
                                                          </select>
                                                          <label class="form-label">Thématiques</label>
                                                          <x-thematique-checkboxes
                                                              :thematiques="$thematiques"
                                                              :selected="array_map('intval', old('thematique_id', collect($document->custom)->pluck('id')->all()))"
                                                          />
                                                          <div class="form-floating my-1">
                                                            <textarea class="form-control" name="resume" placeholder="Description complète (minimum 250 mots)..." id="floatingTextarea2" style="height: 180px">{{$document->resume}}</textarea>
                                                            @error('resume')
                                                            <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                                            @enderror
                                                            <label for="floatingTextarea2">Description <span class="text-muted small">(min. 250 mots, page Lire)</span></label>
                                                          </div>
    
                                                          <div class="form-check mt-2">
                                                            <input class="form-check-input" type="checkbox" name="statut_publication" id="statut_publication_edit"
                                                            {{ $document->statut_publication == 1 ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="statut_publication_edit">
                                                              Publier sur la page d'accueil (visible par tous, sans connexion)
                                                            </label>
                                                          </div>
                                                          <div class="form-check mt-1">
                                                            <input class="form-check-input" type="checkbox" name="ask_form"
                                                            {{ $document->ask_form == 1 ? 'checked' : '' }}>   
                                                            <label class="form-check-label" for="flexCheckDefault">
                                                                Demande de télechargement
                                                            </label>
                                                          </div>
                                                         <div class="float-end"> <button type="submit" class="btn btn-primary mt-2 ">Valider</button></div>
                                                    </div>
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
    </div>
@include('dashboard.components.footer')
    </body>
@endsection
