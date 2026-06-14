@extends('layouts.main')
@section('content')
<div class="container text-center py-5">
    <h1 class="display-1">404</h1>
    <h2>Page introuvable</h2>
    <p class="text-muted">La page que vous recherchez n'existe pas ou a été déplacée.</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Retour à l'accueil</a>
</div>
@endsection
