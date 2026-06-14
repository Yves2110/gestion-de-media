@props(['text'])

<button type="button"
        class="btn btn-link btn-sm p-0 ms-1 form-help-icon d-inline-flex align-items-center"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        data-bs-custom-class="form-help-tooltip"
        title="{{ $text }}"
        aria-label="Aide">
    <x-feather-icon name="help-circle" :size="14" />
</button>
