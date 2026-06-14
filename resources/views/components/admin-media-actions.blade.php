@props([
    'item',
    'type' => 'audio',
])

@php
    $isAudio = $type === 'audio';
    $prefix = $isAudio ? 'audios' : 'videos';
    $previewRoute = $isAudio ? 'public.audios.show' : 'public.videos.show';
    $label = $isAudio ? 'cet audio' : 'cette vidéo';
@endphp

<div class="admin-row-actions">
    @if ($item->statut)
        <x-icon-action icon="eye-off" title="Dépublier" action="{{ route($prefix . '.desactivate', $item->id) }}" variant="warning" />
    @else
        <x-icon-action icon="check-circle" title="Publier" action="{{ route($prefix . '.activate', $item->id) }}" variant="success" />
    @endif
    <x-icon-action icon="eye" title="Aperçu public" href="{{ route($previewRoute, $item) }}" variant="info" target="_blank" />
    <x-icon-action icon="edit-2" title="Éditer" href="{{ route($prefix . '.edit', $item) }}" variant="primary" />
    <x-icon-action icon="trash-2" title="Supprimer" action="{{ route($prefix . '.destroy', $item) }}" method="DELETE" variant="danger" :confirm="'Supprimer ' . $label . ' ?'" />
</div>
