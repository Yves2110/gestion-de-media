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
                                <div class="card ">
                                    <div class="row">
                                        <div class="col-8"></div>
                                    <div class="col-4 ">
                                        <button class="btn btn-primary m-1 float-end ">
                                            <a href="{{ route('audios.create') }}" class="text-white">Ajouter un audio</a>
                                        </button>
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
                                                        <th class="text-nowrap text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($audios as $audio)
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-nowrap">
                                                                {{ $audio->title }}
                                                            </td>
                                                            <td class="text-nowrap">
                                                                {{ $audio->auteur }}
                                                            </td>
                                                            <td class="text-nowrap">
                                                               @if ( $audio->code_media == null )
                                                                   <p class="badge bg-info">Néant</p>
                                                               @else
                                                               {{ $audio->code_media }}
                                                               @endif
                                                            </td>
                                                            <td class="text-nowrap">
                                                                {{ $audio->source->label }}
                                                            </td>
                                                            <td class="text-nowrap">
                                                                @foreach ($audio->custom as $thematique)
                                                                    {{ $thematique->label }}
                                                                @endforeach
                                                            </td>
                                                            <td class="text-nowrap">
                                                               @if ( $audio->description == null)
                                                                   <p class="badge bg-info">Néant</p>
                                                               @else
                                                               {{ $audio->description }}
                                                               @endif
                                                            </td>
                                                            <td class="text-nowrap">
                                                                @if ($audio->statut === 0)
                                                                    <a href="{{ route('activateAudio', $audio->id) }}">
                                                                        <button type="submit" class="btn btn-warning">
                                                                            Publié
                                                                        </button>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('desactivateAudio', $audio->id) }}">
                                                                        <button type="submit" class="btn btn-success">
                                                                            Non Publié
                                                                        </button>
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td class="text-nowrap">
                                                                {{ $audio->user->firstname }} {{ $audio->user->lastname }}
                                                            </td>
                                                            <td class="d-flex text-nowrap">

                                                                @if ($audio->localisation == null)
                                                                <a href="{{route('audioLocalisation',$audio->id)}}"><button type="submit" class="btn btn-dark mx-2">Ajouter localisation</button></a>
                                                                @else
                                                                <a href="{{ route('audios.show', $audio->id) }}">
                                                                    <button type="submit" class="btn btn-warning mx-2">
                                                                       Voir la Localisation
                                                                    </button>
                                                                </a>
                                                                @endif

                                                                <a href="{{ route('audios.edit', $audio->id) }}">
                                                                    <button type="submit" class="btn btn-success">
                                                                        Editer
                                                                    </button>
                                                                </a>
                                                                <form action="{{ route('audios.destroy', $audio->id) }}"
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
                                {{ $audios->links() }}
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
