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
                                            <h6 class="text-uppercase text-center">Formulaire d'ajout d'une localisation</h6>
                                            <form action="{{route('documentLocalisationAdd')}}" method="post">
                                                @csrf
                                                <div class="mb-2">
                                                    <input type="hidden" name="localisation_id" value="{{$document->id}}">
                                                    <label class="form-label">Localisation ( code iframe)</label>
                                                    <textarea name="localisation" id="" class="form-control"  placeholder="Copier-Coller le lien sous format Iframe" rows="10"></textarea>
                                                    @error('localisation')
                                                    <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                                    @enderror
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
