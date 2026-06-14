@props(['name', 'size' => 16, 'class' => ''])

<i data-feather="{{ $name }}" class="{{ $class }}" style="width:{{ $size }}px;height:{{ $size }}px;"></i>
