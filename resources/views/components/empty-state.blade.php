<div class="text-center py-4">

    <div class="mb-2 text-muted empty-state-icon">

        <x-feather-icon name="inbox" :size="40" />

    </div>

    <h6>{{ $title ?? 'Aucun élément' }}</h6>

    <p class="text-muted small">{{ $message ?? '' }}</p>

    @if (!empty($actionUrl))

        <a href="{{ $actionUrl }}" class="btn btn-sm btn-primary">{{ $actionLabel ?? 'Commencer' }}</a>

    @endif

</div>

