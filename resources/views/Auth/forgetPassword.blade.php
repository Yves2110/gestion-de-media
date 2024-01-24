@extends('layouts.main')
@section('content')
    <div class="app-content content mt-5 row ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper ">
            <div class="content-header row">
            </div>
            <div class="content-body   ">
                <div class="auth-wrapper auth-basic px-2">
                    <div class="auth-inner my-2">
                        <!-- Forgot Password basic -->
                        <div class="card mb-0 col-md-4 offset-4">
                            <div class="card-body">
                                @if ($message = Session::get('success'))
                                <div class="alert alert-success mt-1 alert-dismissible" role="alert">
                                    <div class="alert-body d-flex align-items-center">
                                        <span>{{ $message }}</span>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                                <a href="#" class="brand-logo">
                                    <h2 class="brand-text text-primary ms-1 text-center">Mot de passe oublié</h2>
                                </a>
                                <form class="auth-forgot-password-form mt-2" action="{{ route('forget.password.post') }}"
                                    method="POST">
                                    @csrf
                                    <div class="mb-1">
                                        <label for="forgot-password-email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="forgot-password-email"
                                            name="email" placeholder="john@example.com" />
                                        @error('email')<h6 class="fw-bold mt-1 text-danger">{{ $message }}@enderror
                                    </div>
                                    <button class="btn btn-primary w-100" type="submit" tabindex="2">Envoyer le
                                        lien</button>
                                </form>

                                <p class="text-center mt-2">
                                    <a href="/"> <i data-feather="chevron-left"></i> Se connecter </a>
                                </p>
                            </div>

                        </div>
                        <!-- /Forgot Password basic -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
