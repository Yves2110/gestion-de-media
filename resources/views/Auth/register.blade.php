@extends('layouts.main')
@section('content')
    <div class="row my-5 ">
        <div class="card offset-4 col-md-4">
            <div class="card-header">
                <h4 class="text-center font-weight-bold text-uppercase">
                    inscription
                </h4>
            </div>
            <form action="{{ route('register.store') }}" method="post">
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
                    <label class="form-label" ">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="john.doe@email.com"/>
                        @error('email')
                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                        @enderror
                </div>
                <div class="mb-1">
                    <label class="form-label" ">Mot de passe</label>
                    <input type="password" name="password" class="form-control"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                        @error('password')
                        <h6 class="fw-bold mt-1 text-danger">{{ $message }}
                        @enderror
                </div>
                <div class="mb-1">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" class="form-control"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                        @error('confirm_password')
                        <h6 class="fw-bold mt-1 text-danger ">{{ $message }}
                        @enderror
                </div>
                <div class="mb-1">
                    <input type="checkbox" class="form-check-input" id="validationCheckBootstrap" />
                    <label class="form-check-label">Se souvenir de moi</label>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </form>
        </div>
    </div>
@endsection
