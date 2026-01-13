<x-layout>
    <x-slot:title>Welcome to Frozen Wiki</x-slot>

    <div class="position-relative d-flex align-items-center justify-content-center text-center"
        style="height: 100vh; background: #000;">
        <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: 0;">
            <video autoplay muted loop playsinline class="w-100 h-100 object-fit-cover" style="opacity: 0.6;">
                <source src="https://cdn.cloudflare.steamstatic.com/apps/dota2/videos/dota_react/homepage/dota_montage_webm.webm" type="video/webm">
            </video>
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle, rgba(0,0,0,0.2) 0%, #050b14 100%);"></div>
        </div>

        <div class="position-relative z-2 container fade-in-anim">
            <h1 class="display-1 frozen-text mb-4" data-text="FROZEN WIKI">FROZEN WIKI</h1>
            <p class="lead text-light mb-5" style="max-width: 700px; margin: 0 auto; text-shadow: 0 2px 4px black;">
                The ultimate database for the Ancient.
            </p>

            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-lg btn-outline-info px-5 py-3 font-cinzel fw-bold box-neon">GO TO DASHBOARD</a>
            @else
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('login') }}" class="btn btn-lg btn-info px-5 py-3 font-cinzel fw-bold shadow-lg">LOGIN</a>
                    <a href="{{ route('register') }}" class="btn btn-lg btn-outline-light px-5 py-3 font-cinzel fw-bold">REGISTER</a>
                </div>
            @endauth
        </div>
    </div>

    <div class="container-fluid py-5" style="background: #050b14; border-top: 1px solid var(--ice-border);">
        <div class="container">
            <h2 class="text-center frozen-text mb-5" data-text="LATEST NEWS">LATEST NEWS</h2>
            
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    @if($latestPatch)
                    <div class="p-4 rounded position-relative overflow-hidden shadow-lg" 
                         style="background: rgba(0,0,0,0.8); border: 1px solid #00d9ff; box-shadow: 0 0 15px rgba(0, 217, 255, 0.15);">
                        
                        <div class="row align-items-center position-relative z-2">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-black border border-info text-info fw-bold me-2">OFFICIAL NEWS</span>
                                    <span class="text-secondary small">{{ $latestPatch['date'] ?? 'Recent Update' }}</span>
                                </div>
                                <h3 class="text-white font-cinzel mb-1 text-shadow">
                                    Gameplay Update {{ isset($latestPatch['name']) ? str_replace('_', '.', $latestPatch['name']) : 'Latest' }}
                                </h3>
                                <p class="text-secondary small mb-0">Check out the latest balance changes, hero updates, and item tweaks.</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                @if(isset($latestPatch['name']))
                                    <a href="{{ route('patch.show', $latestPatch['name']) }}" class="btn btn-outline-info rounded-pill px-4 btn-sm fw-bold">
                                        Read Patch Notes &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-end mb-4 border-bottom border-secondary border-opacity-25 pb-2">
                <div>
                    <h6 class="text-info font-cinzel mb-1">PRO CIRCUIT</h6>
                    <h2 class="text-white font-cinzel m-0">RECENT MATCHES</h2>
                </div>
                </div>

            <div class="d-flex flex-column gap-3">
                @forelse($esportsMatches as $match)
                    <div class="esport-row p-3 bg-black border border-secondary rounded d-flex flex-wrap align-items-center justify-content-between hover-glow position-relative overflow-hidden">
                        
                        <div class="text-secondary small fw-bold mb-2 mb-md-0" style="min-width: 80px;">
                            {{ \Carbon\Carbon::createFromTimestamp($match['start_time'])->format('d M, H:i') }}
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-3 flex-grow-1 text-end" style="flex-basis: 30%;">
                            <span class="text-white fw-bold font-cinzel {{ $match['radiant_win'] ? 'text-success text-shadow-success' : '' }}">
                                {{ $match['radiant_name'] ?? 'Radiant' }}
                            </span>
                            <div class="team-initial bg-success bg-opacity-25 border border-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold font-cinzel shadow" style="width: 35px; height: 35px;">
                                {{ substr($match['radiant_name'] ?? 'R', 0, 1) }}
                            </div>
                        </div>

                        <div class="mx-3 mx-md-5 px-3 py-1 bg-dark border border-secondary rounded text-white fw-bold small z-2">
                            VS
                        </div>

                        <div class="d-flex align-items-center justify-content-start gap-3 flex-grow-1 text-start" style="flex-basis: 30%;">
                            <div class="team-initial bg-danger bg-opacity-25 border border-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-bold font-cinzel shadow" style="width: 35px; height: 35px;">
                                {{ substr($match['dire_name'] ?? 'D', 0, 1) }}
                            </div>
                            <span class="text-white fw-bold font-cinzel {{ !$match['radiant_win'] ? 'text-success text-shadow-success' : '' }}">
                                {{ $match['dire_name'] ?? 'Dire' }}
                            </span>
                        </div>

                        <div class="mt-3 mt-md-0 text-end" style="min-width: 120px;">
                            <a href="{{ route('match.show', $match['match_id']) }}" class="btn btn-sm btn-outline-info rounded-0 fw-bold px-3" style="letter-spacing: 1px; font-size: 10px;">
                                SERIES DETAILS
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 border border-dashed border-secondary rounded">
                        <p class="text-muted">No live matches currently available.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <style>
        .esport-row { transition: all 0.3s; }
        .esport-row:hover { background: rgba(255,255,255,0.02); border-color: #00d9ff !important; transform: translateX(5px); }
        .text-shadow-success { text-shadow: 0 0 15px rgba(25, 135, 84, 0.8); }
        .team-initial { font-size: 14px; text-shadow: 0 0 5px currentColor; }
    </style>
</x-layout>