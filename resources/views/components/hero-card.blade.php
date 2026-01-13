@props(['hero'])

<div class="col-6 col-md-4 col-lg-2 hero-item" 
     data-name="{{ strtolower($hero->name_localized) }}" 
     data-attr="{{ $hero->primary_attr }}">
    <a href="{{ route('heroes.show', $hero->id) }}" class="text-decoration-none">
        <div class="canvas-card">
            <div class="shader-layer"></div>
            <div class="canvas-img-wrapper">
                <img src="{{ $hero->img_url }}" class="hero-img" alt="{{ $hero->name_localized }}" loading="lazy">
            </div>
            <div class="canvas-details">
                <h6 class="canvas-title text-uppercase font-cinzel small mb-1">{{ $hero->name_localized }}</h6>
                <div class="canvas-subtitle">
                    @php
                        $iconMap = [
                            'str' => 'hero_strength.png',
                            'agi' => 'hero_agility.png',
                            'int' => 'hero_intelligence.png',
                            'all' => 'hero_universal.png',
                        ];
                        $attrIcon = $iconMap[$hero->primary_attr] ?? 'hero_universal.png';
                        $iconUrl = "https://cdn.cloudflare.steamstatic.com/apps/dota2/images/dota_react/icons/$attrIcon";
                    @endphp
                    <img src="{{ $iconUrl }}" alt="{{ $hero->primary_attr }}" width="22" height="22">
                </div>
            </div>
        </div>
    </a>
</div>