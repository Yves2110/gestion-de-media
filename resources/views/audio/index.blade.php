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
                                        <button class="btn btn-primary float-end m-1"><a href="{{route('audio.create')}}"
                                                class="text-white">Ajouter un audio</a></button>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Titre</th>
                                                    <th>Auteur</th>
                                                    <th>Code Audio</th>
                                                    <th>Source</th>
                                                    <th>Thématique</th>
                                                    <th>Description</th>
                                                    <th>Statut</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                          
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            gggg
                                                        </td>
                                                        <td>
                                                            gggg
                                                        </td>
                                                        <td>
                                                            gggg
                                                        </td>
                                                        <td>
                                                            gggg
                                                        </td>
                                                        <td>
                                                            gggg
                                                        </td>
                                                        <td>
                                                            gggg
                                                        </td>
                                                        <td>
                                                            gggg
                                                        </td>
                                                        <td class="d-flex">
                                                            <a href="">
                                                                <button type="submit" class="btn btn-success">
                                                                    Editer
                                                                </button>
                                                            </a>
                                                            <form action=""
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
                                          
                                        </table>
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
