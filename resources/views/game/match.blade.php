<x-layout>
    <x-slot:title>Match {{ $match['match_id'] }} - Frozen Wiki</x-slot>

    <div class="container py-5 fade-in-anim">
        
        <div class="text-center mb-5">
            <h5 class="text-muted text-uppercase mb-2">{{ $match['league']['name'] ?? 'Public Match' }}</h5>
            <h1 class="display-4 font-cinzel text-white">
                <span class="{{ $match['radiant_win'] ? 'text-success' : 'text-danger' }}">{{ $match['radiant_name'] ?? 'Radiant' }}</span>
                <span class="mx-3 text-secondary fs-6">VS</span>
                <span class="{{ !$match['radiant_win'] ? 'text-success' : 'text-danger' }}">{{ $match['dire_name'] ?? 'Dire' }}</span>
            </h1>
            <div class="badge bg-secondary mt-2 fs-6">
                Winner: {{ $match['radiant_win'] ? ($match['radiant_name'] ?? 'Radiant') : ($match['dire_name'] ?? 'Dire') }}
            </div>
            <p class="text-secondary mt-2 small">
                Match ID: {{ $match['match_id'] }} &bull; Duration: {{ gmdate("H:i:s", $match['duration']) }}
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover border border-secondary align-middle">
                <thead>
                    <tr class="text-center small text-secondary text-uppercase" style="background: #0b1116;">
                        <th class="text-start ps-4">Player</th>
                        <th>Lvl</th>
                        <th>K / D / A</th>
                        <th>Net Worth</th>
                        <th>LH / DN</th>
                        <th>GPM / XPM</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($match['players'] as $p)
                        @php
                            $isRadiant = $p['isRadiant'];
                            $heroId = $p['hero_id'];
                            $hero = $heroes[$heroId] ?? null;
                            $slotColor = $isRadiant ? 'border-success' : 'border-danger';
                        @endphp
                        <tr class="{{ $loop->iteration == 6 ? 'border-top border-secondary border-3' : '' }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        @if($hero)
                                            <img src="{{ $hero->img_url }}" class="rounded border {{ $slotColor }}" width="48">
                                        @else
                                            <div class="rounded border {{ $slotColor }} bg-secondary" style="width:48px; height:27px;"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white small">
                                            {{ $p['personaname'] ?? 'Anonymous' }}
                                        </div>
                                        <div class="text-secondary x-small" style="font-size: 10px;">
                                            {{ $hero ? $hero->name_localized : 'Unknown Hero' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-bold text-warning">{{ $p['level'] }}</td>
                            <td class="text-center">
                                <span class="text-success">{{ $p['kills'] }}</span> / 
                                <span class="text-danger">{{ $p['deaths'] }}</span> / 
                                <span class="text-info">{{ $p['assists'] }}</span>
                            </td>
                            <td class="text-center text-warning fw-bold">
                                {{ number_format($p['net_worth'] ?? $p['gold_spent']) }}
                            </td>
                            <td class="text-center text-secondary small">
                                {{ $p['last_hits'] }} / {{ $p['denies'] }}
                            </td>
                            <td class="text-center text-secondary small">
                                {{ $p['gold_per_min'] }} / {{ $p['xp_per_min'] }}
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    @for($i=0; $i<=5; $i++)
                                        @php 
                                            $itemId = $p['item_'.$i]; 
                                            $item = $items[$itemId] ?? null;
                                        @endphp
                                        @if($itemId != 0)
                                            @if($item)
                                                <img src="{{ $item->img_url }}" class="rounded border border-secondary" width="35" title="{{ $item->dname }}">
                                            @else
                                                <div class="bg-secondary rounded border border-secondary" style="width:35px; height:25px;" title="ID: {{ $itemId }}"></div>
                                            @endif
                                        @else
                                            <div class="bg-black border border-secondary border-opacity-25 rounded" style="width:35px; height:25px;"></div>
                                        @endif
                                    @endfor
                                    
                                    @php 
                                        $neutralId = $p['item_neutral'] ?? 0;
                                        $neutral = $items[$neutralId] ?? null;
                                    @endphp
                                    @if($neutralId != 0 && $neutral)
                                        <div class="ms-2 border-start border-secondary ps-2">
                                            <img src="{{ $neutral->img_url }}" class="rounded-circle border border-warning" width="30" title="{{ $neutral->dname }}">
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="text-center mt-4">
            <a href="/" class="btn btn-outline-secondary">&larr; Back to Home</a>
        </div>
    </div>
</x-layout>