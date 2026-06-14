@props(['align' => 'menu'])

@php
    $isContribActive = request()->routeIs('contrib.*') || request()->routeIs('documents.submit*');
@endphp

@if ($align === 'menu')
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ $isContribActive ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Contribuer
        </a>
        <ul class="dropdown-menu portal-dropdown">
            <li><a class="dropdown-item {{ request()->routeIs('contrib.documents.*') ? 'active' : '' }}" href="{{ route('contrib.documents.create') }}">Document</a></li>
            <li><a class="dropdown-item {{ request()->routeIs('contrib.audios.*') ? 'active' : '' }}" href="{{ route('contrib.audios.create') }}">Audio</a></li>
            <li><a class="dropdown-item {{ request()->routeIs('contrib.videos.*') ? 'active' : '' }}" href="{{ route('contrib.videos.create') }}">Vidéo</a></li>
        </ul>
    </li>
@else
    <div class="dropdown d-inline-block">
        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Contribuer
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('contrib.documents.create') }}">Document</a></li>
            <li><a class="dropdown-item" href="{{ route('contrib.audios.create') }}">Audio</a></li>
            <li><a class="dropdown-item" href="{{ route('contrib.videos.create') }}">Vidéo</a></li>
        </ul>
    </div>
@endif
