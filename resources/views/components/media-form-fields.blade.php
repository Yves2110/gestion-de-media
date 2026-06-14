@props([
    'type',
    'item' => null,
    'sources',
    'thematiques',
])

@php
    $isVideo = $type === 'video';
    $typeValue = $isVideo ? 1 : 0;
    $codePrefix = $isVideo ? 'VID' : 'AUD';
    $nextCode = \App\Support\MediaCodeGenerator::generate($typeValue);
    $selectedThematiques = $item
        ? array_map('intval', old('thematique_id', collect($item->custom ?? [])->pluck('id')->all()))
        : array_map('intval', old('thematique_id', []));
@endphp

<x-validation-summary />

<input type="hidden" name="user_id" value="{{ Auth::id() }}">
<input type="hidden" name="type" value="{{ $typeValue }}">

<div class="mb-2">
    <label class="form-label" for="media-title">
        Titre <span class="text-danger">*</span>
    </label>
    <input type="text" name="title" id="media-title"
           @class(['form-control', 'is-invalid' => $errors->has('title')])
           value="{{ old('title', $item->title ?? '') }}"
           placeholder="Ex. Conférence sur l'agriculture durable" required>
    <x-field-error name="title" />
</div>

<div class="mb-2">
    <label class="form-label" for="media-auteur">
        Auteur <span class="text-danger">*</span>
    </label>
    <input type="text" name="auteur" id="media-auteur"
           @class(['form-control', 'is-invalid' => $errors->has('auteur')])
           value="{{ old('auteur', $item->auteur ?? '') }}"
           placeholder="Nom de l'auteur ou de l'organisme" required>
    <x-field-error name="auteur" />
</div>

<div class="mb-2">
    @if ($item?->code_media)
        <div class="media-form-hint d-flex align-items-center gap-2">
            <x-feather-icon name="hash" :size="16" />
            <span>Code média : <strong>{{ $item->code_media }}</strong> (attribué automatiquement, non modifiable)</span>
        </div>
    @else
        <div class="media-form-hint d-flex align-items-center gap-2">
            <x-feather-icon name="hash" :size="16" />
            <span>Code attribué à l'enregistrement : <strong>{{ $nextCode }}</strong> (format {{ $codePrefix }}-XXX)</span>
        </div>
    @endif
</div>

<div class="mb-2">
    <label class="form-label" for="media-source">
        Source <span class="text-danger">*</span>
    </label>
    <select @class(['form-select', 'is-invalid' => $errors->has('source_id')]) name="source_id" id="media-source" required>
        <option value="">Sélectionner une source</option>
        @foreach ($sources as $source)
            <option value="{{ $source->id }}"
                {{ (string) old('source_id', $item->source_id ?? '') === (string) $source->id ? 'selected' : '' }}>
                {{ $source->label }}
            </option>
        @endforeach
    </select>
    <x-field-error name="source_id" />
</div>

<div class="mb-2">
    <label class="form-label">Thématiques <span class="text-danger">*</span></label>
    <div @class(['thematique-checkboxes-wrapper', 'is-invalid-group' => $errors->has('thematique_id') || $errors->has('thematique_id.*')])>
        <x-thematique-checkboxes :thematiques="$thematiques" :selected="$selectedThematiques" />
    </div>
    <x-field-error name="thematique_id" />
</div>

<div class="mb-2">
    <label class="form-label d-inline-flex align-items-center" for="media-link">
        @if ($isVideo)
            Lien vidéo YouTube <span class="text-danger">*</span>
            <x-form-help-icon text="Collez l'URL de la vidéo ou le code d'intégration copié depuis YouTube (Partager > Intégrer)." />
        @else
            Lien audio <span class="text-danger">*</span>
            <x-form-help-icon text="Collez l'URL HTTPS directe du fichier audio (.mp3, .wav, .ogg) ou une balise HTML &lt;audio&gt; avec src=https://…" />
        @endif
    </label>

    @if ($isVideo)
        <div class="media-form-hint">
            <strong>Formats acceptés :</strong>
            <ul class="mb-0 ps-3">
                <li><code>https://www.youtube.com/watch?v=XXXXXXXX</code></li>
                <li><code>https://youtu.be/XXXXXXXX</code></li>
                <li>Code iframe : <code>&lt;iframe src="https://www.youtube.com/embed/XXXXXXXX" …&gt;</code></li>
            </ul>
        </div>
    @else
        <div class="media-form-hint">
            <strong>Formats acceptés :</strong>
            <ul class="mb-0 ps-3">
                <li>URL directe : <code>https://exemple.com/mon-audio.mp3</code></li>
                <li>Balise HTML : <code>&lt;audio controls src="https://exemple.com/audio.mp3"&gt;&lt;/audio&gt;</code></li>
            </ul>
            <p class="mb-0 mt-1 small text-muted">Le lien doit être en <strong>HTTPS</strong> et pointer vers un fichier audio accessible publiquement.</p>
        </div>
    @endif

    <textarea @class(['form-control', 'mt-2', 'font-monospace', 'is-invalid' => $errors->has('media')])
              name="media" id="media-link" rows="{{ $isVideo ? 3 : 2 }}"
              placeholder="{{ $isVideo ? 'https://www.youtube.com/watch?v=…' : 'https://…/fichier.mp3' }}"
              required>{{ old('media', $item->media ?? '') }}</textarea>
    <x-field-error name="media" />
</div>

<div class="mb-2">
    <label class="form-label" for="media-description">Description</label>
    <textarea @class(['form-control', 'is-invalid' => $errors->has('description')])
              name="description" id="media-description" rows="4"
              placeholder="Résumé du contenu (facultatif)">{{ old('description', $item->description ?? '') }}</textarea>
    <x-field-error name="description" />
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="statut" id="media-statut"
           {{ old('statut', ($item->statut ?? 0) == 1 ? 1 : 0) ? 'checked' : '' }}>
    <label class="form-check-label" for="media-statut">
        Publier immédiatement
        <x-form-help-icon text="Si décoché, le contenu reste en brouillon jusqu'à publication manuelle depuis la liste." />
    </label>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                    new bootstrap.Tooltip(el);
                });

                var summary = document.querySelector('.validation-summary');
                if (summary) {
                    summary.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        </script>
    @endpush
@endonce
