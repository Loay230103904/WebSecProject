@props([
    'id',
    'title' => '',
    'size' => 'md',
    'centered' => true,
    'scrollable' => false,
    'fullscreen' => false,
    'staticBackdrop' => false
])

@php
    $modalClasses = [
        'modal-dialog',
        $size === 'sm' ? 'modal-sm' : ($size === 'lg' ? 'modal-lg' : ($size === 'xl' ? 'modal-xl' : '')),
        $centered ? 'modal-dialog-centered' : '',
        $scrollable ? 'modal-dialog-scrollable' : '',
        $fullscreen ? 'modal-fullscreen' : ''
    ];
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true" 
    {{ $staticBackdrop ? 'data-bs-backdrop="static" data-bs-keyboard="false"' : '' }}>
    <div class="{{ implode(' ', array_filter($modalClasses)) }}">
        <div class="modal-content">
            @if($title)
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @endif
            
            <div class="modal-body">
                {{ $slot }}
            </div>
            
            @if(isset($footer))
            <div class="modal-footer">
                {{ $footer }}
            </div>
            @endif
        </div>
    </div>
</div>
