@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'outlined' => false,
    'rounded' => false,
    'icon' => null,
    'iconPosition' => 'start',
    'loading' => false,
    'disabled' => false
])

@php
    $classes = [
        'btn',
        $outlined ? "btn-outline-{$variant}" : "btn-{$variant}",
        $size === 'sm' ? 'btn-sm' : ($size === 'lg' ? 'btn-lg' : ''),
        $rounded ? 'rounded-pill' : '',
        $loading ? 'disabled' : '',
        $disabled ? 'disabled' : ''
    ];
    
    $attributes = $attributes->merge([
        'type' => $type,
        'class' => implode(' ', array_filter($classes)),
        'disabled' => $loading || $disabled ? 'disabled' : false
    ]);
@endphp

<button {{ $attributes }}>
    @if($loading)
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        <span class="visually-hidden">Loading...</span>
    @endif
    
    @if($icon && $iconPosition === 'start')
        <i class="{{ $icon }} me-2"></i>
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'end')
        <i class="{{ $icon }} ms-2"></i>
    @endif
</button>
