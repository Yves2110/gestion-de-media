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
                                    <div class="row">
                                        <div class="col-8"></div>
                                    <div class="col-4 ">
                                        <button class="btn btn-primary float-end m-1"><a href="{{ route('documents.create') }}"
                                            class="text-white">Ajouter un document</a></button>
                                    </div>
                                    </div>
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
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-nowrap">Couverture</th>
                                                    <th scope="col" class="text-nowrap">Titre</th>
                                                    <th scope="col" class="text-nowrap">Auteur</th>
                                                    <th scope="col" class="text-nowrap">Edition</th>
                                                    <th scope="col" class="text-nowrap">Catégorie</th>
                                                    <th scope="col" class="text-nowrap">Nbre de pages</th>
                                                    <th scope="col" class="text-nowrap">Date publication</th>
                                                    <th scope="col" class="text-nowrap">Code document</th>
                                                    <th scope="col" class="text-nowrap">Source</th>
                                                    <th scope="col" class="text-nowrap">Thématique</th>
                                                    <th scope="col" class="text-nowrap">Résumé du document</th>
                                                    <th scope="col" class="text-nowrap">Statut publication</th>
                                                    <th scope="col" class="text-nowrap">Actions</th>
                                                </tr>
                                            </thead>
                                         @foreach ($documents as $document)

                                                <tbody>
                                                    <tr>
                                                        <td class="text-nowrap">
                                                            <img src="{{ asset('picture/' . $document->picture) }}" width="75px" height="75px">
                                                        </td>
                                                        <td class="text-nowrap">
                                                           {{$document->title}}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            {{$document->auteur}}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            {{$document->edition}}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            {{$document->categorie}}
                                                        </td>
                                                       <td class="text-nowrap">
                                                        {{$document->page}}
                                                       </td>
                                                        <td class="text-nowrap">
                                                            {{ date('d-M-Y', strtotime($document->publication_date))}}
                                                           
                                                        </td>
                                                        <td  class="text-nowrap">
                                                           @if ( $document->code_document == null)
                                                               <p class="badge bg-info">Néant</p>
                                                           @else
                                                           {{$document->code_document}}
                                                           @endif
                                                        </td>
                                                        <td class="text-nowrap">
                                                            {{$document->source->label}}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            @foreach ($document->custom as $thematique)
                                                                {{ $thematique->label }}
                                                            @endforeach 
                                                        </td>
                                                        <td class="text-nowrap" style=" 
                                                            max-width: 100px;
                                                            overflow: scroll ;
                                                          
                                                          ">
                                                          <p>
                                                            @if ( $document->resume == null)
                                                               <p class="badge bg-info">Néant</p>
                                                           @else
                                                           {{$document->resume}}
                                                           @endif
                                                          </p>
                                                        </td>
                                                        <td  class="text-nowrap">
                                                          
                                                             @if ($document->statut_publication === 0)
                                                                <a href="{{ route('activateDocument', $document->id) }}">
                                                                    <button type="submit" class="btn btn-warning">
                                                                        Publié
                                                                    </button>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('desactivateDocument', $document->id) }}">
                                                                    <button type="submit" class="btn btn-success">
                                                                        Non Publié
                                                                    </button>
                                                                </a>
                                                            @endif 
                                                        </td>
                                                        <td class="d-flex mt-2 text-nowrap">

                                                            @if ($document->localisation == null)
                                                            <a href="{{route('documentLocalisation',$document->id)}}"><button type="submit" class="btn btn-dark mx-2">Ajouter localisation</button></a>
                                                            @else
                                                            <a href="{{ route('documents.show', $document->id) }}">
                                                                <button type="submit" class="btn btn-warning mx-2">
                                                                   Voir la Localisation
                                                                </button>
                                                            </a>
                                                            @endif

                                                            <a href="{{route('documents.edit',$document->id)}}">
                                                                <button type="submit" class="btn btn-success">
                                                                    Editer
                                                                </button>
                                                            </a>
                                                            <form action="{{ route('documents.destroy', $document->id) }}"
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
                               {{$documents->links()}}
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
