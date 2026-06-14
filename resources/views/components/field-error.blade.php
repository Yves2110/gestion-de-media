@props(['name'])

@error($name)
    <div {{ $attributes->merge(['class' => 'field-error-message']) }} role="alert">
        {{ $message }}
    </div>
@enderror
