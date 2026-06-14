@props([
    'name',
    'id' => null,
    'required' => false,
    'size' => null,
    'class' => '',
    'placeholder' => null,
    'autocomplete' => null,
    'value' => null,
])

@php
    $inputId = $id ?? 'field-' . $name;
    $inputClasses = trim('form-control' . ($size === 'sm' ? ' form-control-sm' : '') . ($class ? ' ' . $class : ''));
@endphp

<div class="password-input-wrapper">
    <input
        type="password"
        name="{{ $name }}"
        id="{{ $inputId }}"
        class="{{ $inputClasses }}"
        @if($required) required @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        value="{{ $value ?? old($name) }}"
        {{ $attributes->except(['name', 'id', 'required', 'size', 'class', 'placeholder', 'autocomplete', 'value']) }}
    />
    <button type="button" class="password-toggle-btn" aria-label="Afficher le mot de passe" title="Afficher le mot de passe">
        <span class="password-toggle-icon password-toggle-icon--show" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </span>
        <span class="password-toggle-icon password-toggle-icon--hide" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
        </span>
    </button>
</div>
