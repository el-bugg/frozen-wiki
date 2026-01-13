<x-layout>
    <x-slot:title>Create Thread - Frozen Wiki</x-slot>

    <div class="container py-5 fade-in-anim">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="p-4 p-md-5 bg-black border border-secondary rounded shadow-lg">
                    <h2 class="frozen-text mb-4" data-text="START DISCUSSION">START DISCUSSION</h2>

                    <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
                        @csrf
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-8">
                                <label class="form-label text-ice font-cinzel">Topic Title</label>
                                <input type="text" name="title" class="form-control bg-dark border-secondary text-white py-2" required placeholder="e.g., Unstoppable Magic Build for Invoker">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-ice font-cinzel">Category</label>
                                <select name="type" id="typeSelect" class="form-select bg-dark border-secondary text-white py-2" onchange="toggleFormSections()">
                                    <option value="general">General Discussion</option>
                                    <option value="hero">Hero Strategy</option>
                                    <option value="item">Item Discussion</option>
                                </select>
                            </div>
                        </div>

                        <div id="heroSelectDiv" class="mb-4 d-none">
                            <label class="form-label text-white small">Select Hero</label>
                            <select id="heroInput" class="form-select bg-dark border-secondary text-white">
                                <option value="">-- Choose Hero --</option>
                                @foreach($heroes as $hero)
                                    <option value="{{ $hero->id }}">{{ $hero->name_localized }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="itemSelectDiv" class="mb-4 d-none">
                            <label class="form-label text-white small">Select Item (Topic Subject)</label>
                            <select id="itemInput" class="form-select bg-dark border-secondary text-white">
                                <option value="">-- Choose Item --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->dname }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <input type="hidden" name="related_id" id="finalRelatedId">

                        <div id="buildCreatorSection" class="mb-5 d-none border border-info border-opacity-50 rounded p-4 bg-dark bg-opacity-25">
                            <h4 class="text-info font-cinzel mb-3">ITEM BUILD RECOMMENDATION</h4>
                            <p class="text-secondary small mb-3">Select items to recommend for each game phase.</p>

                            <div class="mb-3">
                                <input type="text" id="itemSearch" class="form-control bg-black border-secondary text-white form-control-sm mb-2" placeholder="Search item to add...">
                                <div class="d-flex gap-2 overflow-auto py-2 custom-scroll" id="itemPool" style="white-space: nowrap;">
                                    @foreach($items as $item)
                                        <div class="item-option d-inline-block position-relative" 
                                             data-id="{{ $item->id }}" 
                                             data-name="{{ strtolower($item->dname) }}" 
                                             data-img="{{ $item->img_url }}"
                                             onclick="selectItem(this)"
                                             style="cursor: pointer; transition: 0.2s;">
                                            <img src="{{ $item->img_url }}" class="rounded border border-secondary" width="45" title="{{ $item->dname }}">
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-secondary fst-italic">Click an item above, then click a Phase below to add it.</small>
                            </div>

                            <div class="row g-3">
                                @foreach(['early_game' => 'Early Game', 'mid_game' => 'Mid Game', 'late_game' => 'Late Game', 'situational' => 'Situational'] as $key => $label)
                                <div class="col-md-6">
                                    <div class="p-3 bg-black border border-secondary rounded h-100 build-phase" onclick="addToPhase('{{ $key }}')">
                                        <h6 class="text-white small fw-bold text-uppercase mb-2">{{ $label }}</h6>
                                        <div id="container_{{ $key }}" class="d-flex flex-wrap gap-1 min-h-item">
                                            </div>
                                        <div id="inputs_{{ $key }}"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-ice font-cinzel">Upload Image / GIF (Optional)</label>
                            <input type="file" name="image" class="form-control bg-dark border-secondary text-white" accept="image/*">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-ice font-cinzel">Discussion Content</label>
                            <textarea name="body" rows="6" class="form-control bg-dark border-secondary text-white" required placeholder="Explain your strategy..."></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('community.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-info fw-bold px-5">Post Thread</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot:scripts>
    <style>
        .min-h-item { min-height: 40px; }
        .build-phase { cursor: pointer; transition: 0.2s; }
        .build-phase:hover { border-color: var(--ice-blue) !important; background: rgba(0, 217, 255, 0.05); }
        .selected-item-glow img { border-color: #00d9ff !important; box-shadow: 0 0 10px #00d9ff; transform: scale(1.1); }
    </style>

    <script>
        let selectedItemData = null;

        function toggleFormSections() {
            const type = document.getElementById('typeSelect').value;
            const heroDiv = document.getElementById('heroSelectDiv');
            const itemDiv = document.getElementById('itemSelectDiv');
            const buildDiv = document.getElementById('buildCreatorSection');
            const finalInput = document.getElementById('finalRelatedId');
            
            heroDiv.classList.add('d-none');
            itemDiv.classList.add('d-none');
            buildDiv.classList.add('d-none');
            finalInput.value = '';

            if (type === 'hero') {
                heroDiv.classList.remove('d-none');
                buildDiv.classList.remove('d-none');
            } else if (type === 'item') {
                itemDiv.classList.remove('d-none');
            }
        }

        // Logic pilih Hero/Item dropdown
        document.getElementById('heroInput').addEventListener('change', function() {
            document.getElementById('finalRelatedId').value = this.value;
        });
        document.getElementById('itemInput').addEventListener('change', function() {
            document.getElementById('finalRelatedId').value = this.value;
        });

        // 1. Logic Pilih Item dari Pool
        function selectItem(element) {
            // Hapus highlight lama
            document.querySelectorAll('.item-option').forEach(el => el.classList.remove('selected-item-glow'));
            
            // Highlight item baru
            element.classList.add('selected-item-glow');
            
            // Simpan data item yang sedang dipilih
            selectedItemData = {
                id: element.dataset.id,
                img: element.dataset.img
            };
        }

        // 2. Logic Masukkan Item ke Phase (Early/Mid/etc)
        function addToPhase(phaseKey) {
            if (!selectedItemData) return; // Tidak ada item yang dipilih

            const container = document.getElementById('container_' + phaseKey);
            const inputContainer = document.getElementById('inputs_' + phaseKey);

            // Tampilkan Gambar Item di Phase
            const imgDiv = document.createElement('div');
            imgDiv.innerHTML = `<img src="${selectedItemData.img}" width="35" class="rounded border border-secondary" onclick="this.parentElement.remove(); removeFromInput('${phaseKey}', '${selectedItemData.id}')">`;
            container.appendChild(imgDiv);

            // Buat Hidden Input agar terkirim ke Controller
            // name="early_game[]", name="mid_game[]", dst.
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = phaseKey + '[]'; 
            input.value = selectedItemData.id;
            input.id = `input_${phaseKey}_${selectedItemData.id}`;
            inputContainer.appendChild(input);

            // Reset seleksi (opsional, biar user pilih lagi)
            // selectedItemData = null; 
            // document.querySelectorAll('.item-option').forEach(el => el.classList.remove('selected-item-glow'));
        }

        function removeFromInput(phaseKey, itemId) {
            const input = document.getElementById(`input_${phaseKey}_${itemId}`);
            if(input) input.remove();
        }

        // 3. Search Filter Logic
        document.getElementById('itemSearch').addEventListener('keyup', function() {
            const val = this.value.toLowerCase();
            document.querySelectorAll('.item-option').forEach(el => {
                if(el.dataset.name.includes(val)) {
                    el.style.display = 'inline-block';
                } else {
                    el.style.display = 'none';
                }
            });
        });
    </script>
    </x-slot>
</x-layout>