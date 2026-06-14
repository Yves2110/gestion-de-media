@props([
    'icon',
    'title',
    'href' => null,
    'action' => null,
    'method' => 'POST',
    'variant' => 'primary',
    'confirm' => null,
    'target' => null,
])

@php
    $classes = 'btn btn-icon btn-sm btn-' . $variant;
@endphp

@if ($action)
    <form action="{{ $action }}" method="POST" class="admin-icon-action-form" @if($confirm) onsubmit="return confirm('{{ $confirm }}')" @endif>
        @csrf
        @if (strtoupper($method) !== 'POST')
            @method($method)
        @endif
        <button type="submit" class="{{ $classes }}" title="{{ $title }}" aria-label="{{ $title }}">
            <i data-feather="{{ $icon }}"></i>
        </button>
    </form>
@else
    <a href="{{ $href }}" class="{{ $classes }}" title="{{ $title }}" aria-label="{{ $title }}" @if($target) target="{{ $target }}" @endif @if($target === '_blank') rel="noopener" @endif>
        <i data-feather="{{ $icon }}"></i>
    </a>
@endif
