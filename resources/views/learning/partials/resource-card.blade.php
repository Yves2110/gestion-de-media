@php
    $typeLabels = ['audio' => 'Audio', 'video' => 'Vidéo', 'document' => 'Document'];
    $typeClass = 'type-' . $resource['type'];
    $label = $typeLabels[$resource['type']] ?? 'Ressource';
    $isVideo = $resource['type'] === 'video';
    $iconName = match ($resource['type']) {
        'audio' => 'headphones',
        'video' => 'video',
        default => 'file-text',
    };
@endphp

<div class="col-6 col-md-4 col-lg-3 mb-4">
    <a href="{{ $resource['url'] }}" class="publication-card learning-resource-card text-decoration-none {{ $isVideo ? 'publication-card-video' : '' }}">
        <div class="publication-card-thumb {{ $typeClass }}">
            @if ($resource['thumbnail'])
                <img src="{{ $resource['thumbnail'] }}" alt="{{ $resource['title'] }}" loading="lazy">
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
            <h6 class="publication-card-title">{{ $resource['title'] }}</h6>
            @if (!empty($resource['description']))
                <p class="publication-card-description">{{ $resource['description'] }}</p>
            @endif
            <p class="publication-card-meta mb-0">{{ $resource['auteur'] }}</p>
            @if (!empty($resource['source']))
                <small class="text-muted">{{ $resource['source'] }}</small>
            @endif
        </div>
    </a>
</div>
