@props(['ability', 'isActive' => false])

<div class="card-frozen h-100 d-flex flex-column position-relative overflow-hidden {{ $isActive ? 'active-glow' : '' }}">
    <div class="mist-bg"></div>
    <div class="d-flex p-3 border-bottom border-ice position-relative z-2 bg-black bg-opacity-50">
        @if($ability->img_url && !str_contains($ability->img_url, 'null'))
            <img src="{{ $ability->img_url }}" class="skill-icon border border-secondary me-3 shadow-sm">
        @endif
        <div class="flex-grow-1">
            <h5 class="text-white fw-bold text-uppercase mb-0 font-cinzel">{{ $ability->dname ?? $ability->name }}</h5>
            <div class="mt-1">
                @php
                    $bText = strtolower($ability->behavior ?? 'Passive');
                    $badges = explode(',', $bText);
                @endphp
                @foreach(array_slice($badges, 0, 2) as $badge) 
                    <span class="badge bg-secondary bg-opacity-50 border border-secondary text-light" style="font-size: 9px;">
                        {{ trim(strtoupper($badge)) }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
    <div class="p-3 position-relative z-2 flex-grow-1 d-flex flex-column">
        <div class="d-flex justify-content-between mb-3 small fw-bold">
            @if($ability->mana_cost)
                <div class="text-info">
                    <span class="fs-6">💧</span> MANA: {{ is_array($ability->mana_cost) ? implode('/', $ability->mana_cost) : $ability->mana_cost }}
                </div>
            @endif
            @if($ability->cooldown)
                <div class="text-white">
                    <span class="fs-6">⏱️</span> CD: {{ is_array($ability->cooldown) ? implode('/', $ability->cooldown) : $ability->cooldown }}s
                </div>
            @endif
        </div>
        <p class="text-light small description-text flex-grow-1">
            {{ $ability->desc }}
        </p>
    </div>
</div>
<style>
    .skill-icon { width: 48px; height: 48px; object-fit: cover; border-radius: 4px; }
    .description-text { color: #b0c4de !important; line-height: 1.6; text-shadow: 1px 1px 2px black; }
    .mist-bg {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(135deg, rgba(0,217,255,0.05) 0%, rgba(0,0,0,0) 100%);
        z-index: 1;
    }
    .active-glow {
        box-shadow: 0 0 20px rgba(0, 217, 255, 0.4);
        border-color: var(--ice-blue) !important;
    }
</style>