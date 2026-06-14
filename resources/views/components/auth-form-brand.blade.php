@props(['title', 'subtitle' => null])

<div class="auth-form-brand text-center">
    <a href="{{ route('home') }}" class="text-decoration-none d-inline-flex flex-column align-items-center">
        <img src="{{ asset('assets/images/favicon.svg') }}" alt="Gestion Media" class="auth-form-favicon" width="40" height="40">
        <h4 class="fw-bold auth-form-title mb-0 mt-2">{{ $title }}</h4>
    </a>
    @if ($subtitle)
        <p class="text-muted auth-form-subtitle mb-0">{{ $subtitle }}</p>
    @endif
</div>
