@props(['field' => 'statut_publication', 'id' => 'contrib-publish'])

@if (auth()->check() && auth()->user()->isAdmin())
    <div class="form-check contrib-form-publish">
        <input class="form-check-input" type="checkbox" name="{{ $field }}" id="{{ $id }}"
               @checked(old($field))>
        <label class="form-check-label" for="{{ $id }}">
            Publier immédiatement sur la page d'accueil
        </label>
    </div>
@endif
