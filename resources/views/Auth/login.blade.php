@extends('layouts.main')
@section('content')
    <div class="row my-5 ">
        <div class="card offset-4 col-md-4 p-2">
            <div class="card-header">
                <h4 class="text-center font-weight-bold text-uppercase">
                    CONNEXION
                </h4>
            </div>
            @if ($message = Session::get('message'))
                <div class="alert alert-danger mt-1 alert-dismissible" role="alert">
                    <div class="alert-body d-flex align-items-center">
                        <span>{{ $message }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="mb-1">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                        placeholder="john.doe@email.com" />
                    @error('email')
                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                        @enderror
                </div>
                <div class="mb-1">
                    <div class="d-flex justify-content-between">
                        <label class="form-label">Mot de passe</label>
                        <a href="{{route('forget.password.get')}}">
                            <small>Mot de passe oublié?</small>
                        </a>
                    </div>
                    <input type="password" class="form-control" name="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                    @error('password')
                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                        @enderror
                </div>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
        </div>
    </div>
@endsection
