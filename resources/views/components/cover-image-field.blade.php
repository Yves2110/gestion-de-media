@props([
    'current' => null,
    'inputId' => 'cover-picture',
    'compact' => false,
    'hint' => null,
])

<div class="mb-2">
    <label class="form-label" for="{{ $inputId }}">
        Image de couverture <span class="text-muted small">(facultatif)</span>
    </label>
    @if ($hint)
        <p class="form-text mb-1">{{ $hint }}</p>
    @endif
    @if ($current)
        <div class="mb-2">
            <img src="{{ asset('storage/picture/' . $current) }}"
                 width="75" height="75"
                 class="rounded object-fit-cover"
                 alt="Couverture actuelle">
        </div>
    @endif
    <input type="file"
           name="picture"
           id="{{ $inputId }}"
           @class([$compact ? 'form-control form-control-sm' : 'form-control', 'is-invalid' => $errors->has('picture')])
           accept="image/*">
    <x-field-error name="picture" />
</div>
