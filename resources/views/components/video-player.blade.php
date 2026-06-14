@props(['content'])

<div class="ratio ratio-16x9 video-player-frame rounded overflow-hidden">
    <x-safe-media :content="$content" />
</div>
