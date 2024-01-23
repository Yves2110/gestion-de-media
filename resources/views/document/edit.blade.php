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
                                            <form action="{{ route('documents.update',$document->id) }}" method="post" enctype="multipart/form-data">
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
                                                        <input type="text" value="{{$document->categorie}}"  name="categorie" class="form-control ">
                                                        @error('categorie')
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
                                                            <img src="{{ asset('picture/' . $document->picture) }}" width="75px" height="75px">
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
                                                          <label class="form-label" for="basicSelect">Sélection une thématique</label> 
                                                          <select class="form-select" name="thematique_id[]" multiple aria-label="multiple select example">
                                                            <option >Open this select menu</option>
                                                            @foreach ($thematiques as $thematique)
                                                            <option value="{{ $thematique->id }}"
                                                                @if (in_array($thematique, $document->custom)) selected @endif>
                                                                {{ $thematique->label }}
                                                            </option>
                                                            @endforeach
                                                          </select>
                                                          <h6 class="text-danger fw-bold mt-1">Maintenir ctrl + clic droit de la souris pour selectionner plusieurs thémaques</h6>
                                                          <div class="form-floating my-1">
                                                            <textarea class="form-control" name="resume"  placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px">{{$document->resume}}</textarea>
                                                            @error('resume')
                                                            <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                                            @enderror
                                                            <label for="floatingTextarea2">Resumé</label>
                                                          </div>
    
                                                          <div class="form-check mt-2">
                                                            <input class="form-check-input" type="checkbox" name="statut_publication"
                                                            {{ $document->statut_publication == 1 ? 'checked' : '' }}>   
                                                            <label class="form-check-label" for="flexCheckDefault">
                                                                Demande de publication
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
    @include('dashboard.components.footer')
    </body>
@endsection
