@props([
    'theme' => 'primary',
    'icon' => 'star',
    'title' => 'Title',
    'description' => '',
    'badge' => 'Badge',
    'features' => [],
    'btnText' => 'Button',
    'btnLink' => '#',
    'btnClass' => 'btn-primary text-white'
])

@php
    // Define Theme Colors based on the passed 'theme' prop
    // This allows keeping the cleaner simpler props in the parent view
    $colors = [
        'primary' => [
            'text' => 'text-primary',
            'bg_soft' => 'bg-primary bg-opacity-10',
            'border_soft' => 'border-primary border-opacity-10',
            'gradient' => 'linear-gradient(180deg, rgba(95, 45, 237, 0.05) 0%, rgba(255,255,255,0) 50%)'
        ],
        'success' => [
            'text' => 'text-success',
            'bg_soft' => 'bg-success bg-opacity-10',
            'border_soft' => 'border-success border-opacity-10',
            'gradient' => 'linear-gradient(180deg, rgba(25,135,84,0.05) 0%, rgba(255,255,255,0) 50%)'
        ],
        'warning' => [
            'text' => 'text-warning',
            'bg_soft' => 'bg-warning bg-opacity-10',
            'border_soft' => 'border-warning border-opacity-10',
            'gradient' => 'linear-gradient(180deg, rgba(255,193,7,0.05) 0%, rgba(255,255,255,0) 50%)'
        ],
        'info' => [
             'text' => 'text-info',
            'bg_soft' => 'bg-info bg-opacity-10',
            'border_soft' => 'border-info border-opacity-10',
            'gradient' => 'linear-gradient(180deg, rgba(13,202,240,0.05) 0%, rgba(255,255,255,0) 50%)'
        ]
    ];

    $c = $colors[$theme] ?? $colors['primary'];
@endphp

@if($attributes->has('wrapCol') && $attributes->get('wrapCol') == false)
    <div class="glass-card h-100 p-0 overflow-hidden d-flex flex-column border-0 shadow-lg position-relative rounded-4 hover-shadow transition-hover bg-white" 
         style="transition: all 0.3s ease;">
@else
    <div class="col-12 col-lg-4">
        <div class="card h-100 p-0 overflow-hidden d-flex flex-column border shadow position-relative rounded-4 hover-shadow transition-hover bg-white" 
             style="transition: all 0.3s ease;">
@endif
        
        {{-- Decorative Background Icon --}}
        <div class="position-absolute top-0 end-0 p-3 opacity-10 pe-none">
            <span class="material-icons-outlined {{ $c['text'] }}" 
                  style="font-size: 8rem; transform: rotate(15deg) translate(20px, -20px);">
                  {{ $icon }}
            </span>
        </div>

        <div class="p-4 flex-grow-1 d-flex flex-column position-relative z-index-1"
             style="background: {{ $c['gradient'] }};">
            
            {{-- Header: Icon Box + Badge --}}
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="{{ $c['bg_soft'] }} p-2 rounded-3 {{ $c['text'] }} shadow-sm">
                    <span class="material-icons-outlined fs-3">{{ $icon }}</span>
                </div>
                <span class="badge {{ $c['bg_soft'] }} {{ $c['text'] }} border {{ $c['border_soft'] }} shadow-sm">
                    {{ $badge }}
                </span>
            </div>

            <h5 class="fw-bold text-dark mb-1">{{ $title }}</h5>
            <p class="small text-secondary mb-3">{{ $description }}</p>
            
            {{-- Feature List --}}
            <ul class="list-unstyled small text-secondary mb-4 opacity-75">
                 @foreach($features as $feature)
                    <li class="d-flex align-items-center mb-1">
                        <span class="material-icons-outlined fs-6 me-2 {{ $c['text'] }}">{{ $feature['icon'] ?? 'check_circle' }}</span> 
                        {{ $feature['label'] }}
                    </li>
                 @endforeach
            </ul>

            <div class="mt-auto">
                {{-- Button Logic --}}
                @if(isset($btnSlot))
                     {{ $btnSlot }}
                @else
                    <a href="{{ $btnLink }}" class="btn {{ $btnClass }} rounded-pill btn-sm w-100 fw-bold shadow-sm">
                        {{ $btnText }}
                    </a>
                @endif
            </div>
        </div>

        {{-- Optional Footer Slot for Dashboard Cards --}}
        @if(isset($footer))
             <div class="bg-glass p-3 border-top position-relative z-index-1">
                 {{ $footer }}
             </div>
        @endif

@if($attributes->has('wrapCol') && $attributes->get('wrapCol') == false)
    </div>
@else
        </div>
    </div>
@endif
