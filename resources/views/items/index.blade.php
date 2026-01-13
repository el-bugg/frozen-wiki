<x-layout>
    <x-slot:title>Artifacts Archive - Frozen Wiki</x-slot>
    
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

    <div class="container py-5 text-center fade-in-anim">
        <h1 class="display-1 mb-3 frozen-text text-uppercase" data-text="ITEM LIST">ITEM LIST</h1>

        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="input-group input-group-lg shadow" style="border: 1px solid #444; border-radius: 5px;">
                    <span class="input-group-text bg-dark border-0 text-secondary">🔍</span>
                    <input type="text" id="itemSearch" class="form-control bg-dark text-white border-0" placeholder="Search item...">
                </div>
            </div>
        </div>

        <div id="itemGrid">
            @foreach($groupedItems as $category => $items)
                <div class="category-section mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <h3 class="text-ice font-cinzel mb-0 text-uppercase">{{ $category }}</h3>
                        <div class="ms-3 flex-grow-1" style="height: 1px; background: linear-gradient(to right, var(--ice-blue), transparent);"></div>
                    </div>

                    <div class="row g-2 justify-content-start">
                        @foreach($items as $item)
                            <div class="col-auto item-card-wrapper" data-name="{{ strtolower($item->dname) }}">
                                <div class="item-canvas border border-secondary bg-dark position-relative overflow-hidden" 
                                     style="width: 85px; height: 64px; cursor: pointer;"
                                     onclick="window.location='{{ route('items.show', $item->id) }}'"
                                     data-tippy-html="#tooltip-{{ $item->id }}">
                                    
                                    <img src="{{ $item->img_url }}" class="img-fluid w-100 h-100 object-fit-cover shadow">
                                    <div class="holo-sheen"></div>
                                    
                                    <div class="position-absolute bottom-0 end-0 bg-black px-1 border-top border-start border-secondary" style="opacity: 0.8;">
                                        <small class="text-warning fw-bold" style="font-size: 10px;">{{ $item->cost }}</small>
                                    </div>
                                </div>

                                <div id="tooltip-{{ $item->id }}" style="display: none;">
                                    <div class="p-3 bg-dark text-white border border-secondary text-start" style="min-width: 300px; background: #151921;">
                                        <div class="d-flex justify-content-between align-items-start mb-3 border-bottom border-secondary pb-2">
                                            <div class="d-flex gap-2">
                                                <img src="{{ $item->img_url }}" width="45" height="34" class="border border-secondary">
                                                <div>
                                                    <h5 class="m-0 fw-bold text-white font-cinzel" style="font-size: 14px;">{{ $item->dname }}</h5>
                                                    <span class="text-secondary small" style="font-size: 10px;">
                                                        {{ !empty($item->components) ? 'Artifact' : 'Item' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-warning fw-bold d-flex align-items-center gap-1">
                                                🟡 {{ number_format($item->cost) }}
                                            </div>
                                        </div>

                                        @if($item->stats)
                                            <div class="mb-2" style="font-size: 11px;">
                                                @foreach($item->stats as $key => $val)
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-secondary">{{ $key }}</span>
                                                        <span class="text-white fw-bold">{{ $val }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($item->mana_cost || $item->cooldown)
                                            <div class="d-flex gap-3 mb-2 border-top border-secondary pt-2" style="font-size: 11px;">
                                                @if($item->mana_cost)
                                                    <div class="text-info">💧 {{ $item->mana_cost }}</div>
                                                @endif
                                                @if($item->cooldown)
                                                    <div class="text-white">⏱️ {{ $item->cooldown }}s</div>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <div class="text-center mt-2 text-info" style="font-size: 9px;">Click for details</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <x-slot:scripts>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Init Tippy.js (Tooltip Engine)
                tippy('.item-canvas', {
                    content(reference) {
                        const id = reference.getAttribute('data-tippy-html');
                        const template = document.querySelector(id);
                        return template.innerHTML;
                    },
                    allowHTML: true,
                    placement: 'right',
                    animation: 'fade',
                    theme: 'light-border',
                    interactive: false,
                    appendTo: document.body,
                });

                // Search Logic
                const searchInput = document.getElementById('itemSearch');
                if(searchInput) {
                    searchInput.addEventListener('input', function(e) {
                        const term = e.target.value.toLowerCase();
                        document.querySelectorAll('.item-card-wrapper').forEach(item => {
                            const name = item.getAttribute('data-name');
                            item.style.display = name.includes(term) ? 'block' : 'none';
                        });
                    });
                }
            });
        </script>
        
        <style>
            .holo-sheen {
                position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
                background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
                transform: skewX(-25deg);
                transition: 0.5s;
            }
            .item-canvas:hover .holo-sheen { left: 150%; transition: 0.7s; }
            .item-canvas:hover { 
                border-color: #00d9ff !important; 
                box-shadow: 0 0 10px #00d9ff; 
                transform: scale(1.1);
                z-index: 10; 
            }
        </style>
    </x-slot:scripts>
</x-layout>