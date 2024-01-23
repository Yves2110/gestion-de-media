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
                            <div class="col-4 offset-4">
                                <div class="card">

                                    <div class="card-body">
                                            <h6 class="text-uppercase text-center">Formulaire de modification d'une source</h6>
                                            <form action="{{ route('source.update',$source->id) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-2">
                                                    <label class="form-label">Source</label>
                                                    <input type="text" name="label" value="{{$source->label}}" class="form-control ">
                                                    @error('label')
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
