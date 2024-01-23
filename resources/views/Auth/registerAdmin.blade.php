@extends('layouts.dashboard')
@section('dashboard_content')
    @include('dashboard.components.nav')
    @include('dashboard.components.menu');
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="row ">
            <div class="card offset-4 col-md-4">
                <div class="card-header">
                    @if ($message = Session::get('message'))
                        <div class="alert alert-success mt-1 alert-dismissible" role="alert">
                            <div class="alert-body d-flex align-items-center">
                                <span>{{ $message }}</span>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <h4 class="m-auto font-weight-bold text-uppercase">
                    Ajout d'administrateur
                </h4>
                <form action="{{ route('admin.store') }}" method="post">
                    @csrf
                    <div class="mb-1">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}"
                            placeholder="Veuillez entrer votre Nom" aria-label="Name" />
                        @error('firstname')
                            <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                            @enderror
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Prénom(s)</label>
                        <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}"
                            placeholder="Veuillez entrer votre prénom" />
                        @error('firstname')
                            <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                            @enderror
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="john.doe@email.com"/>
                            @error('email')
        <h6 class="fw-bold mt-1 text-danger">{{ $message }}
    @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                    <a class="btn btn-outline-primary my-2" href="{{ route('userManage') }}">Retour</a>
                </form>
            </div>
        </div>
        </div>
        @include('dashboard.components.footer')
@endsection
