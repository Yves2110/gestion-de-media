@extends('layouts.dashboard')
@section('dashboard_content')
    @include('dashboard.components.nav')
    @include('dashboard.components.menu');
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="row">
            <div class="col-md-10 offset-1">
                <!-- profile -->
                <div class="card ">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Details du Profil</h4>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success mt-1 alert-dismissible" role="alert">
                            <div class="alert-body d-flex align-items-center">
                                <span>{{ $message }}</span>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card-body py-2 my-25">
                        <form action="{{ route('changeData') }}" class="validate-form mt-2 pt-50" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="accountFirstName">Nom </label>
                                    <input type="text" class="form-control" id="accountFirstName" name="firstname"
                                        value="{{ Auth::user()->firstname }}" placeholder="John" />
                                    @error('firstname')
                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                        @enderror
                                </div>
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="accountLastName">Prénom(s)</label>
                                    <input type="text" class="form-control" id="accountLastName" name="lastname"
                                        value="{{ Auth::user()->lastname }}" placeholder="Doe" />
                                    @error('lastname')
                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                        @enderror
                                </div>
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="accountEmail">Email</label>
                                    <input type="email" class="form-control" id="accountEmail" name="email"
                                        placeholder="Email" value="{{ Auth::user()->email }}" />
                                    @error('email')
                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                        @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mt-1 me-1">Modifier</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- deactivate account  -->
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Changer de mot de passe</h4>
                    </div>
                    <div class="card-body py-2 my-25">
                        <form action="{{ route('changePassword') }}" class="validate-form mt-2 pt-50" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label">Nouveau mot de passe </label>
                                    <input type="password" class="form-control" name="password" />
                                    <div class="text-warning fw-bold">Nb :Le mot de passe doit contenir au moin une
                                        majuscule,8
                                        caractères et des chiffres</div>
                                    @error('password')
                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                        @enderror
                                </div>
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label">Confirmer Mot de passe</label>
                                    <input type="password" class="form-control" name="confirm_password" />
                                    @error('confirm_password')
                                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                                        @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mt-1 me-1">Modifier</button>
                                </div>
                            </div>
                        </form>
                        <!--/ form -->
                    </div>
                </div>
                <!--/ profile -->
            </div>
        </div>
    </div>
    @include('dashboard.components.footer')
@endsection
