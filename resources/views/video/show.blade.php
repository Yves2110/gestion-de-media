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
                            <div class="col-md-6 offset-3">
                                <div class="card">
                                    <div class="card-body">
                                        {!! $video->localisation !!}
                                    </div>
                                   <div class="d-flex  m-2">
                                    <a href="{{route('videoLocalisation',$video->id)}}}}">
                                        <button type="submit" class="btn btn-success">Changer de carte</button>
                                    </a>
                                    <form action="{{route('destroyLocalisation',$video->id)}}">
                                        <input type="hidden" name="localisation_id" value="{{$video->id}}">
                                    <a href="{{ route('destroyLocalisation',$video->id) }}" class="mx-2">
                                        <button type="submit" class="btn btn-danger">Retirer</button>
                                    </a>
                                </form>
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
