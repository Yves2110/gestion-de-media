@props(['publication'])

@php
    $typeLabels = ['audio' => 'Audio', 'video' => 'Vidéo', 'document' => 'Document'];
    $typeClass = 'type-' . $publication['type'];
    $label = $typeLabels[$publication['type']] ?? 'Ressource';
    $iconName = match ($publication['type']) {
        'audio' => 'headphones',
        'video' => 'video',
        default => 'file-text',
    };
    $isVideo = $publication['type'] === 'video';
@endphp

<div class="col-6 col-md-4 col-lg-3 mb-4">
    <a href="{{ $publication['url'] }}" class="publication-card text-decoration-none {{ $isVideo ? 'publication-card-video' : '' }}">
        <div class="publication-card-thumb {{ $typeClass }}">
            @if ($publication['thumbnail'])
                <img src="{{ $publication['thumbnail'] }}" alt="{{ $publication['title'] }}">
                @if ($isVideo)
                    <span class="video-play-overlay" aria-hidden="true">
                        <x-feather-icon name="play" :size="28" />
                    </span>
                @endif
            @else
                <div class="publication-card-placeholder">
                    <x-feather-icon :name="$iconName" :size="36" />
                </div>
            @endif
            <span class="publication-type-badge">{{ $label }}</span>
        </div>
        <div class="publication-card-body">
            <h6 class="publication-card-title">{{ $publication['title'] }}</h6>
            @if (!empty($publication['description']))
                <p class="publication-card-description">{{ $publication['description'] }}</p>
            @endif
            <p class="publication-card-meta mb-0">{{ $publication['auteur'] }}</p>
            @if ($publication['date'])
                <small class="text-muted">{{ $publication['date']->format('d/m/Y') }}</small>
            @endif
        </div>
    </a>
</div>
