@props(['title' => '', 'subtitle' => '', 'footer' => false, 'glass' => false])

<div {{ $attributes->merge(['class' => 'card ' . ($glass ? 'glass' : '')]) }}>
    @if($title)
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0">{{ $title }}</h5>
            @if($subtitle)
            <p class="card-subtitle text-muted small mb-0">{{ $subtitle }}</p>
            @endif
        </div>
        @if(isset($headerActions))
        <div class="card-actions">
            {{ $headerActions }}
        </div>
        @endif
    </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
    
    @if($footer)
    <div class="card-footer">
        {{ $footer }}
    </div>
    @endif
</div>
