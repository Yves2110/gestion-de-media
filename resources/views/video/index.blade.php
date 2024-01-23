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
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-8"></div>
                                    <div class="col-4 ">
                                        <button class="btn btn-primary float-end m-1"><a href="{{route('videos.create')}}"
                                            class="text-white">Ajouter une video</a></button>
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
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-nowrap">Titre</th>
                                                            <th scope="col" class="text-nowrap">Auteur</th>
                                                            <th scope="col" class="text-nowrap">Code Audio</th>
                                                            <th scope="col" class="text-nowrap">Source</th>
                                                            <th scope="col" class="text-nowrap">Thématique</th>
                                                            <th scope="col" class="text-nowrap">Description</th>
                                                            <th scope="col" class="text-nowrap">Statut</th>
                                                            <th scope="col" class="text-nowrap">
                                                                Enregistré par
                                                            </th>
                                                            <th scope="col" class="text-nowrap">Action</th>
                                                    </tr>
                                                </thead>
                                              @foreach ($videos as $video)
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-nowrap">
                                                               {{$video->title}}
                                                            </td>
                                                            <td class="text-nowrap">
                                                                {{$video->auteur}}
                                                            </td>
                                                            <td class="text-nowrap">
                                                                @if ($video->code_media == null)
                                                                    <p class="badge bg-info">
                                                                        Néant
                                                                    </p>
                                                                @else
                                                                {{$video->code_media}}
                                                                @endif
                                                            </td>
                                                            <td class="text-nowrap">
                                                                {{$video->source->label}}
                                                            </td>
                                                                <td class="text-nowrap">
                                                                    @foreach ($video->custom as $thematique)
                                                                        {{ $thematique->label }}
                                                                    @endforeach
                                                                </td>
                                                            <td class="text-nowrap">
                                                                @if ( $video->description == null )
                                                                    <p class="badge bg-info">
                                                                        Néant
                                                                    </p>
                                                                @else
                                                                {{$video->description}}
                                                                @endif
                                                            </td>
                                                            {{-- <td>
                                                                {!! $video->media !!}
                                                            </td> --}}
                                                            <td class="text-nowrap">
                                                                @if ($video->statut_publication === 0)
                                                                    <a href="{{ route('activateVideo', $video->id) }}">
                                                                        <button type="submit" class="btn btn-success">
                                                                            Publié
                                                                        </button>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('desactivateVideo', $video->id) }}">
                                                                        <button type="submit" class="btn btn-warning">
                                                                            Non Publié
                                                                        </button>
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td class="text-nowrap">
                                                                {{$video->user->firstname}} {{$video->user->lastname}}
                                                            </td>
                                                            <td class="d-flex text-nowrap">
                                                                @if ($video->localisation == null)
                                                                <a href="{{route('videoLocalisation',$video->id)}}"><button type="submit" class="btn btn-dark mx-2">Ajouter localisation</button></a>
                                                                @else
                                                                <a href="{{ route('videos.show', $video->id) }}">
                                                                    <button type="submit" class="btn btn-warning mx-2">
                                                                       Voir la Localisation
                                                                    </button>
                                                                </a>
                                                                @endif

                                                                <a href="{{route('videos.edit',$video->id)}}">
                                                                    <button type="submit" class="btn btn-success">
                                                                        Editer
                                                                    </button>
                                                                </a>
                                                                <form action="{{ route('videos.destroy', $video->id) }}"
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
                                {{$videos->links()}}
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
