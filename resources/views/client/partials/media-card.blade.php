@props(['item', 'type', 'showRoute' => null])

@php
    use App\Support\MediaThumbnail;
    use Illuminate\Support\Str;

    $typeLabels = ['audio' => 'Audio', 'video' => 'Vidéo', 'document' => 'Document'];
    $typeColors = ['audio' => 'primary', 'video' => 'info', 'document' => 'success'];
    $label = $typeLabels[$type] ?? ucfirst($type);
    $color = $typeColors[$type] ?? 'secondary';
    $link = $showRoute ?? route('catalogue.show', ['type' => $type, 'id' => $item->id]);
    $thumbUrl = $type === 'document'
        ? ($item->picture ? asset('storage/picture/' . $item->picture) : null)
        : MediaThumbnail::url($item);
@endphp

<div class="col-md-6 col-lg-4 mb-3">
    <div class="card client-card h-100 shadow-sm {{ $type === 'video' ? 'video-media-card' : '' }}">
        @if ($thumbUrl)
            @if ($type === 'video')
                <a href="{{ $link }}" class="video-card-preview">
                    <img src="{{ $thumbUrl }}" alt="{{ $item->title }}" class="card-img-top">
                    <span class="video-play-overlay" aria-hidden="true">
                        <x-feather-icon name="play" :size="28" />
                    </span>
                </a>
            @else
                <img src="{{ $thumbUrl }}" class="card-img-top" alt="couverture" style="height:140px;object-fit:cover;">
            @endif
        @endif
        <div class="card-body d-flex flex-column">
            <div class="mb-2">
                <span class="badge bg-{{ $color }}">{{ $label }}</span>
                @if ($item->source ?? null)
                    <span class="badge bg-light-secondary">{{ $item->source->label }}</span>
                @endif
            </div>
            <h6 class="card-title">
                <a href="{{ $link }}" class="text-decoration-none">{{ $item->title }}</a>
            </h6>
            @if ($type === 'video' && !empty($item->description))
                <p class="text-muted small video-card-description">{{ Str::limit(strip_tags($item->description), 160) }}</p>
            @endif
            <p class="text-muted small mb-1">{{ $item->auteur }}</p>
            @if (!empty($item->custom))
                <div class="mb-2">
                    @foreach ($item->custom as $thematique)
                        <span class="badge bg-light-primary me-1">{{ $thematique->label }}</span>
                    @endforeach
                </div>
            @endif
            @if ($item->created_at ?? null)
                <small class="text-muted mb-2">{{ $item->created_at->format('d/m/Y') }}</small>
            @endif
            <div class="mt-auto d-flex gap-1">
                <a href="{{ $link }}" class="btn btn-sm btn-primary">
                    @if ($type === 'audio') Écouter @elseif ($type === 'video') Regarder @else Consulter @endif
                </a>
                @if ($type === 'document')
                    <a href="{{ route('catalogue.documents.download', $item) }}" class="btn btn-sm btn-outline-primary">PDF</a>
                @endif
            </div>
        </div>
    </div>
</div>
