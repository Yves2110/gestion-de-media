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
                                        <h6 class="text-uppercase text-center">Formulaire de modification d'un audio</h6>
                                        <form action="{{ route('audios.update', $audio->id) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-2">
                                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                                    <input type="hidden" name="type" value="0">
                                                    <label class="form-label">Titre</label>
                                                    <input type="text" name="title" value="{{$audio->title}}" class="form-control ">
                                                    @error('title')
                                                    <h6 class="fw-bold mt-1 text-danger">{{ $message }}</h6>
                                                    @enderror
                                                    <label class="form-label">Auteur</label>
                                                    <input type="text" name="auteur" value="{{$audio->auteur}}" class="form-control ">
                                                    @error('auteur')
                                                    <h6 class="fw-bold mt-1 text-danger">{{ $message }}</h6>
                                                    @enderror
                                                    <label class="form-label">Code Média</label>
                                                    <input type="text" name="code_media" value="{{$audio->code_media}}" class="form-control ">
                                                    @error('code_media')
                                                    <h6 class="fw-bold mt-1 text-danger">{{ $message }}</h6> 
                                                    @enderror
                                                <select class="form-select my-2" name="source_id"
                                                    aria-label="Default select example">
                                                    <option selected>Selectionner une source</option>
                                                    @foreach ($sources as $source)
                                                        <option value="{{ $source->id }}"
                                                            {{ $audio->source_id == $source->id ? 'selected' : '' }}>
                                                            {{ $source->label }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <label class="form-label" for="basicSelect">Sélection une thématique</label>
                                                <select class="form-select" name="thematique_id[]" multiple
                                                    aria-label="multiple select example">
                                                    @foreach ($thematiques as $thematique)
                                                        <option value="{{ $thematique->id }}"
                                                            @if (in_array($thematique, $audio->custom)) selected @endif>
                                                            {{ $thematique->label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <h6 class="text-warning fw-bold">
                                                    Maintenir ctrl + clic droit de la souris pour selectionner plusieurs
                                                    thématiques
                                                </h6>

                                                <div class="my-2">
                                                    <label for="formFile" class="form-label">Changer le lien audio</label>
                                                    <input class="form-control" name="media" value="{{$audio->media}}" type="text" id="formFile" onchange="displaySelectedFileName()">
                                                    <p id="selectedFileName"></p>
                                                </div>
                                                
                                                <div class="form-floating my-1">
                                                    <label for="floatingTextarea2" class="mt-3">Modifier la description</label>
                                                    <textarea class="form-control" name="description" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px">{{ $audio->description }}</textarea>
                                                </div>
                                                

                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="statut"
                                                        {{ $audio->statut == 1 ? 'checked' : '' }}>
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
