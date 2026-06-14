@props(['thematiques', 'selected' => [], 'name' => 'thematique_id'])

<div class="thematique-checkbox-group">
    @forelse ($thematiques as $thematique)
        <label class="thematique-checkbox-item">
            <input
                type="checkbox"
                name="{{ $name }}[]"
                value="{{ $thematique->id }}"
                @checked(in_array($thematique->id, $selected))
            >
            <span>{{ $thematique->label }}</span>
        </label>
    @empty
        <p class="text-muted small mb-0">Aucune thématique disponible.</p>
    @endforelse
</div>
@error('thematique_id')
    <div class="text-danger small mt-1">{{ $message }}</div>
@enderror
@error('thematique_id.*')
    <div class="text-danger small mt-1">{{ $message }}</div>
@enderror
