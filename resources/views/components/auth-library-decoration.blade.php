<div class="auth-library-scene" aria-hidden="true">
    <div class="auth-library-glow"></div>
    <div class="auth-books-orbit">
        @php
            $colors = ['#eb3349', '#f45c43', '#d62839', '#ff6b4a', '#c9184a', '#e85d4c', '#b51742', '#ff7f50'];
        @endphp
        @foreach ($colors as $index => $color)
            <div class="auth-book-orbit-item" style="--orbit-angle: {{ $index * 45 }}deg;">
                @include('components.auth-book-icon', ['cover' => $color])
            </div>
        @endforeach
    </div>
    <div class="auth-library-shelves">
        <span></span><span></span><span></span>
    </div>
    <div class="auth-library-center">
        <div class="auth-library-logo">
            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                <rect width="32" height="32" rx="8" fill="rgba(255,255,255,0.15)"/>
                <path d="M8 10h7v16H8a1.5 1.5 0 01-1.5-1.5v-13A1.5 1.5 0 018 10zm9 0h7a1.5 1.5 0 011.5 1.5v13a1.5 1.5 0 01-1.5 1.5h-7V10z" fill="#fff"/>
                <path d="M16 10v16" stroke="#ffd6d6" stroke-width="1"/>
            </svg>
        </div>
        <h2 class="auth-library-title">Gestion Media</h2>
        <p class="auth-library-tagline">Votre bibliothèque numérique</p>
        <p class="auth-library-sub">Documents | Vidéos | Audios</p>
    </div>
</div>
