<x-layout>
    {{-- LOGIC REPLACE DI SINI --}}
    @php
        $displayVersion = str_replace('_', '.', $version);
    @endphp

    <x-slot:title>Patch {{ $displayVersion }} - Frozen Wiki</x-slot>

    <div class="container py-5 fade-in-anim">
        <div class="text-center mb-5">
            <span class="badge bg-info mb-2">GAMEPLAY UPDATE</span>
            {{-- GUNAKAN VARIABLE BARU --}}
            <h1 class="display-3 frozen-text" data-text="PATCH {{ $displayVersion }}">PATCH {{ $displayVersion }}</h1>
            <p class="text-secondary">Official Changelog</p>
            
            @if(isset($releaseDate))
                <small class="text-ice fst-italic">Released on {{ $releaseDate }}</small>
            @endif
        </div>
        
        {{-- Sisa kode ke bawah tetap sama... --}}
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                @if(isset($patchContent['general']) && count($patchContent['general']) > 0)
                <div class="mb-5">
                    <h3 class="text-warning font-cinzel border-bottom border-secondary pb-2 mb-4">GENERAL UPDATES</h3>
                    <ul class="list-group list-group-flush bg-transparent">
                        @foreach($patchContent['general'] as $change)
                            <li class="list-group-item bg-transparent text-light border-secondary">
                                <span class="text-info">✦</span> 
                                @if(is_array($change))
                                    {!! implode('<br>', $change) !!}
                                @else
                                    {!! $change !!}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(isset($patchContent['heroes']) && count($patchContent['heroes']) > 0)
                <div class="mb-5">
                    <h3 class="text-ice font-cinzel border-bottom border-secondary pb-2 mb-4">HERO UPDATES</h3>
                    <div class="row g-4">
                        @foreach($patchContent['heroes'] as $heroName => $changes)
                            @php
                                $hero = \App\Models\Hero::where('code_name', $heroName)->first();
                            @endphp
                            <div class="col-md-6">
                                <div class="p-3 border border-secondary bg-black bg-opacity-50 h-100 rounded">
                                    <div class="d-flex align-items-center mb-3 border-bottom border-secondary border-opacity-50 pb-2">
                                        @if($hero)
                                            <img src="{{ $hero->img_url }}" class="rounded-circle me-3 border border-info" width="40" height="40">
                                            <h4 class="text-white mb-0 font-cinzel">{{ $hero->name_localized }}</h4>
                                        @else
                                            <h4 class="text-white mb-0 font-cinzel">{{ str_replace('npc_dota_hero_', '', $heroName) }}</h4>
                                        @endif
                                    </div>
                                    <ul class="list-unstyled mb-0 ps-2">
                                        @foreach($changes as $change)
                                            <li class="text-secondary small mb-2">
                                                <span class="text-danger">•</span> 
                                                @if(is_array($change))
                                                    {!! implode('<br> &nbsp; - ', $change) !!}
                                                @else
                                                    {!! $change !!}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($patchContent['items']) && count($patchContent['items']) > 0)
                <div class="mb-5">
                    <h3 class="text-warning font-cinzel border-bottom border-secondary pb-2 mb-4">ITEM UPDATES</h3>
                    <div class="row g-3">
                        @foreach($patchContent['items'] as $itemName => $changes)
                            @php
                                $item = \App\Models\Item::where('key_name', $itemName)->first();
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 border border-secondary bg-black bg-opacity-50 h-100 rounded position-relative">
                                    <div class="d-flex align-items-center mb-2">
                                        @if($item)
                                            <img src="{{ $item->img_url }}" class="rounded me-2 border border-warning" width="35">
                                            <h5 class="text-white mb-0 small">{{ $item->dname }}</h5>
                                        @else
                                            <h5 class="text-white mb-0 small">{{ $itemName }}</h5>
                                        @endif
                                    </div>
                                    <ul class="list-unstyled mb-0 ps-2">
                                        @foreach($changes as $change)
                                            <li class="text-secondary x-small mb-1" style="font-size: 0.8rem;">
                                                • 
                                                @if(is_array($change))
                                                    {!! implode('<br> &nbsp; - ', $change) !!}
                                                @else
                                                    {!! $change !!}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-layout>