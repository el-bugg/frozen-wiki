<x-layout>
    <x-slot:title>{{ $hero->name_localized }} - Frozen Wiki</x-slot>

    <div class="hero-header-container bg-black position-relative overflow-hidden d-flex align-items-center justify-content-center fade-in-anim"
        style="height: 600px; border-bottom: 2px solid var(--ice-border);">
        @if ($hero->video_url)
            <div class="video-wrapper">
                <video autoplay muted loop playsinline disablePictureInPicture class="hero-video no-interaction">
                    <source src="{{ $hero->video_url }}" type="video/webm">
                </video>
                <div class="video-overlay-ice"></div>
            </div>
        @endif
        <div class="container position-relative z-2 text-center header-content" style="margin-top: 150px;">
            <img src="{{ $hero->icon_url }}" class="rounded-circle shadow-lg border border-2 border-info mb-3" width="64">
            <h1 class="display-1 fw-bold text-uppercase frozen-text mb-0" data-text="{{ $hero->name_localized }}">
                {{ $hero->name_localized }}
            </h1>
            <p class="text-ice lead fw-bold text-uppercase mt-2" style="letter-spacing: 4px; font-size: 0.9rem; text-shadow: 0 2px 5px black;">
                {{ $hero->attack_type }} &bull; {{ $hero->primary_attr }}
            </p>
        </div>
    </div>

    <div class="container position-relative z-3 fade-in-anim mobile-reset-margin" style="margin-top: -60px;">
        <div class="row g-4 align-items-start">
            
            <div class="col-lg-4 mb-4">
                <x-ice-card style="height: auto !important;">
                    <div class="text-center mb-2">
                        <img src="{{ $hero->img_url }}" class="img-fluid border border-secondary w-100 shadow-lg" alt="{{ $hero->name_localized }}">
                    </div>

                    <div class="mb-4 px-1">
                        @php
                            $hp = $hero->base_health + ($hero->base_str * 22);
                            $mp = $hero->base_mana + ($hero->base_int * 12);
                            $hpRegen = $hero->base_health_regen + ($hero->base_str * 0.1);
                            $mpRegen = $hero->base_mana_regen + ($hero->base_int * 0.05);
                        @endphp
                        <div class="progress mb-1 position-relative rounded-0" style="height: 24px; background: #1c1c1c; border: 1px solid #000;">
                            <div class="progress-bar bg-success" style="width: 100%;"></div>
                            <span class="position-absolute w-100 text-center text-white fw-bold text-shadow small" style="line-height: 24px; font-size: 12px;">
                                {{ $hp }} <span class="text-white-50" style="font-size: 10px;">+{{ number_format($hpRegen, 1) }}</span>
                            </span>
                        </div>
                        <div class="progress position-relative rounded-0" style="height: 24px; background: #1c1c1c; border: 1px solid #000;">
                            <div class="progress-bar bg-primary" style="width: 100%;"></div>
                            <span class="position-absolute w-100 text-center text-white fw-bold text-shadow small" style="line-height: 24px; font-size: 12px;">
                                {{ $mp }} <span class="text-white-50" style="font-size: 10px;">+{{ number_format($mpRegen, 1) }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="row text-center mb-3 g-1">
                        <div class="col-4">
                            <div class="p-2 border border-secondary bg-black bg-opacity-50 rounded">
                                <img src="https://cdn.cloudflare.steamstatic.com/apps/dota2/images/dota_react/icons/hero_strength.png" width="24" class="mb-1">
                                <h5 class="text-white fw-bold mb-0">{{ $hero->base_str }}</h5>
                                <small class="text-secondary" style="font-size: 10px;">+{{ $hero->str_gain }}</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 border border-secondary bg-black bg-opacity-50 rounded">
                                <img src="https://cdn.cloudflare.steamstatic.com/apps/dota2/images/dota_react/icons/hero_agility.png" width="24" class="mb-1">
                                <h5 class="text-white fw-bold mb-0">{{ $hero->base_agi }}</h5>
                                <small class="text-secondary" style="font-size: 10px;">+{{ $hero->agi_gain }}</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 border border-secondary bg-black bg-opacity-50 rounded">
                                <img src="https://cdn.cloudflare.steamstatic.com/apps/dota2/images/dota_react/icons/hero_intelligence.png" width="24" class="mb-1">
                                <h5 class="text-white fw-bold mb-0">{{ $hero->base_int }}</h5>
                                <small class="text-secondary" style="font-size: 10px;">+{{ $hero->int_gain }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <div class="d-flex justify-content-between px-3 py-2 bg-black border border-secondary rounded stats-row">
                            <span class="text-secondary small text-uppercase">Movement Speed</span>
                            <span class="text-white fw-bold">{{ $hero->move_speed }}</span>
                        </div>
                        <div class="d-flex justify-content-between px-3 py-2 bg-black border border-secondary rounded stats-row">
                            <span class="text-secondary small text-uppercase">Armor</span>
                            <span class="text-white fw-bold">{{ number_format($hero->base_armor, 1) }}</span>
                        </div>
                        <div class="d-flex justify-content-between px-3 py-2 bg-black border border-secondary rounded stats-row">
                            <span class="text-secondary small text-uppercase">Attack Range</span>
                            <span class="text-white fw-bold">{{ $hero->attack_range }}</span>
                        </div>
                    </div>
                </x-ice-card>
            </div>

            <div class="col-lg-8">
                
                <div class="mb-5">
                    <h3 class="frozen-text mb-3" data-text="ABILITIES">ABILITIES</h3>
                    
                    <div id="abilitiesCarousel" class="carousel slide border border-info shadow-lg bg-black" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($hero->abilities as $index => $ability)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <div class="row g-0">
                                        <div class="col-md-7 position-relative">
                                            @if($ability->video_url)
                                                <video class="d-block w-100 object-fit-cover" autoplay muted loop playsinline style="height: 350px;">
                                                    <source src="{{ $ability->video_url }}" type="video/mp4">
                                                </video>
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-dark" style="height: 350px;">
                                                    <img src="{{ $ability->img_url }}" class="w-50 opacity-50">
                                                </div>
                                            @endif
                                            <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-to-t">
                                                <h4 class="text-white text-uppercase font-cinzel text-shadow mb-0">{{ $ability->dname ?? $ability->name }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-5 p-4 d-flex flex-column bg-dark border-start border-secondary" style="min-height: 350px;">
                                            <div class="d-flex align-items-center mb-3">
                                                <img src="{{ $ability->img_url }}" class="border border-secondary me-3" width="50" height="50">
                                                <div>
                                                    <div class="badge bg-info text-dark mb-1">{{ strtoupper($ability->behavior ?? 'PASSIVE') }}</div>
                                                    @if($ability->cooldown)
                                                        <div class="text-white small">⏱️ {{ is_array($ability->cooldown) ? implode('/', $ability->cooldown) : $ability->cooldown }}</div>
                                                    @endif
                                                    @if($ability->mana_cost)
                                                        <div class="text-info small">💧 {{ is_array($ability->mana_cost) ? implode('/', $ability->mana_cost) : $ability->mana_cost }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="overflow-auto custom-scroll flex-grow-1" style="max-height: 200px;">
                                                <p class="text-secondary small mb-0">{{ $ability->desc }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#abilitiesCarousel" data-bs-slide="prev" style="width: 5%;">
                            <span class="carousel-control-prev-icon bg-black bg-opacity-50 p-3 rounded" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#abilitiesCarousel" data-bs-slide="next" style="width: 5%;">
                            <span class="carousel-control-next-icon bg-black bg-opacity-50 p-3 rounded" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>

                <div class="mb-5">
                    <h3 class="frozen-text mb-3" data-text="LORE">LORE</h3>
                    <div class="p-4 bg-black border-start border-4 border-info shadow-lg">
                        <div class="position-relative">
                            <div id="loreText" class="text-light small text-clamp-3" style="line-height: 1.8;">
                                {!! nl2br(e($hero->lore ?? 'No lore available.')) !!}
                            </div>
                            <button onclick="toggleText('loreText', this)" class="btn btn-sm btn-link text-info p-0 text-decoration-none mt-2 fw-bold" style="font-size: 11px;">READ MORE ▼</button>
                        </div>
                    </div>
                </div>

                @if($hero->playstyle || $hero->pros || $hero->cons)
                <div class="mb-5">
                    <h3 class="frozen-text mb-4" data-text="STRATEGY GUIDE">STRATEGY GUIDE</h3>
                    
                    @if($hero->playstyle)
                    <div class="p-4 bg-black border-start border-4 border-info shadow-lg mb-4">
                        <h5 class="text-ice font-cinzel mb-2">PLAYSTYLE</h5>
                        <div class="position-relative">
                            <div id="playstyleText" class="text-light small text-clamp-3" style="line-height: 1.8;">
                                {{ $hero->playstyle }}
                            </div>
                            <button onclick="toggleText('playstyleText', this)" class="btn btn-sm btn-link text-info p-0 text-decoration-none mt-2 fw-bold" style="font-size: 11px;">READ MORE ▼</button>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row g-4">
                        @if($hero->pros)
                        <div class="col-md-6">
                            <div class="p-3 border border-success bg-black bg-opacity-50 h-100 rounded">
                                <h5 class="text-success font-cinzel mb-3">PROS</h5>
                                <ul class="text-light small mb-0 ps-3">
                                    @foreach($hero->pros as $pro) <li class="mb-1">{{ $pro }}</li> @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                        @if($hero->cons)
                        <div class="col-md-6">
                            <div class="p-3 border border-danger bg-black bg-opacity-50 h-100 rounded">
                                <h5 class="text-danger font-cinzel mb-3">CONS</h5>
                                <ul class="text-light small mb-0 ps-3">
                                    @foreach($hero->cons as $con) <li class="mb-1">{{ $con }}</li> @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="frozen-text mb-0" data-text="RECOMMENDED BUILD">RECOMMENDED BUILD</h3>
                        @auth
                            <a href="{{ route('community.create') }}" class="btn btn-sm btn-outline-info">Create Build</a>
                        @endauth
                    </div>

                    @php 
                        // Ambil hanya 1 build teratas
                        $topBuild = isset($builds) ? $builds->first() : null; 
                    @endphp

                    @if($topBuild)
                        <div class="bg-black border border-secondary rounded p-4 mb-4 shadow-sm">
                            <div class="d-flex justify-content-between mb-3 border-bottom border-secondary border-opacity-25 pb-2">
                                <div>
                                    <h5 class="text-white font-cinzel mb-0">{{ $topBuild->title }}</h5>
                                    <small class="text-secondary">by {{ $topBuild->user->name ?? 'Unknown' }} &bull; {{ $topBuild->created_at->diffForHumans() }}</small>
                                </div>
                                </div>

                            <div class="row g-4">
                                @foreach(['early_game' => 'EARLY', 'mid_game' => 'MID', 'late_game' => 'LATE', 'situational' => 'SITUATIONAL'] as $phaseKey => $phaseLabel)
                                    @if(!empty($topBuild->$phaseKey))
                                    <div class="col-md-3">
                                        <h6 class="text-secondary x-small mb-2 fw-bold text-uppercase" style="letter-spacing: 1px;">{{ $phaseLabel }}</h6>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($topBuild->$phaseKey as $itemId)
                                                @if(isset($allItems[$itemId]))
                                                    <a href="{{ route('items.show', $itemId) }}" data-bs-toggle="tooltip" title="{{ $allItems[$itemId]->dname }}">
                                                        <img src="{{ $allItems[$itemId]->img_url }}" class="rounded border border-secondary" width="40" height="29">
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5 border border-dashed border-secondary rounded opacity-50">
                            <p class="text-muted mb-2">No recommended builds yet.</p>
                            <small class="text-secondary">Be the first to share your strategy!</small>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <div class="row mt-5 pt-4 border-top border-secondary border-opacity-25">
            <div class="col-6">
                @if($prevHero)
                <a href="{{ route('heroes.show', $prevHero->id) }}" class="nav-hero-card d-flex align-items-center text-decoration-none p-3 border border-secondary rounded bg-black">
                    <img src="{{ $prevHero->img_url }}" class="rounded-circle border border-secondary me-3" width="50" height="50">
                    <div class="text-start">
                        <small class="text-secondary d-block text-uppercase">Previous Hero</small>
                        <span class="text-white font-cinzel fs-5">{{ $prevHero->name_localized }}</span>
                    </div>
                </a>
                @endif
            </div>
            <div class="col-6 text-end">
                @if($nextHero)
                <a href="{{ route('heroes.show', $nextHero->id) }}" class="nav-hero-card d-flex align-items-center justify-content-end text-decoration-none p-3 border border-secondary rounded bg-black">
                    <div class="text-end me-3">
                        <small class="text-secondary d-block text-uppercase">Next Hero</small>
                        <span class="text-white font-cinzel fs-5">{{ $nextHero->name_localized }}</span>
                    </div>
                    <img src="{{ $nextHero->img_url }}" class="rounded-circle border border-secondary" width="50" height="50">
                </a>
                @endif
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            function toggleText(elementId, btn) {
                const el = document.getElementById(elementId);
                if (el.classList.contains('text-clamp-3')) {
                    el.classList.remove('text-clamp-3');
                    btn.innerText = 'SHOW LESS ▲';
                } else {
                    el.classList.add('text-clamp-3');
                    btn.innerText = 'READ MORE ▼';
                }
            }
            
            document.addEventListener("DOMContentLoaded", function() {
                ['loreText', 'playstyleText'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el && el.scrollHeight <= el.clientHeight) {
                        const btn = el.nextElementSibling;
                        if(btn) btn.style.display = 'none';
                    }
                });
            });
        </script>
        
        <style>
            .nav-hero-card { transition: all 0.3s; }
            .nav-hero-card:hover { border-color: var(--ice-blue) !important; box-shadow: 0 0 15px rgba(0, 217, 255, 0.2); transform: translateY(-3px); }
            .bg-gradient-to-t { background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); }
            
            .text-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .custom-scroll::-webkit-scrollbar { width: 5px; }
            .custom-scroll::-webkit-scrollbar-track { background: #000; }
            .custom-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
            .custom-scroll::-webkit-scrollbar-thumb:hover { background: var(--ice-blue); }
        </style>
    </x-slot>
</x-layout>