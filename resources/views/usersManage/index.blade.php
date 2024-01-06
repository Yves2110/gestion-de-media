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
                                        <button class="btn btn-primary float-end m-1"><a href="{{route('addAdmin')}}" class="text-white">Ajouter un administrateur</a></button>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Noms</th>
                                                    <th>Prénom(s)</th>
                                                    <th>Emails</th>
                                                    <th>Rôle</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            @foreach ($admins as $admin)
                                            <tbody>
                                                <tr>
                                                    <td>
                                                     {{$admin->firstname}}
                                                    </td>
                                                    <td>
                                                        {{$admin->lastname}}
                                                       </td>
                                                    <td>
                                                        {{$admin->email}}
                                                    </td>
                                                    <td>
                                                        {{$admin->role->label}}</td>
                                                   <td>
                                                    @if ($admin->statut === 1)
                                                    <a href="{{ route('desactivate',$admin->id) }}">
                                                        <button type="submit" class="btn btn-warning">
                                                            Désactiver
                                                        </button>
                                                    </a>
                                                    @else
                                                    <a href="{{ route('activate',$admin->id) }}">
                                                         <button type="submit" class="btn btn-success">
                                                             Activer
                                                         </button>
                                                     </a>
                                                    @endif
                                                    <a href="{{ route('removeManager',$admin->id) }}">
                                                        <button type="submit" class="btn btn-danger">
                                                            Supprimer
                                                        </button>
                                                       </a>
                                                   </td>
                                                </tr>
                                            </tbody>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                                {{ $admins->links() }}
                            </div>
                        </div>
                        <!-- Basic Tables end -->

                </section>
            

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    @include('dashboard.components.footer')
@endsection