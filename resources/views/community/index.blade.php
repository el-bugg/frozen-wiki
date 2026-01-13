<x-layout>
    <x-slot:title>Community - Frozen Wiki</x-slot>

    <div class="position-relative py-5 mb-5" style="background: linear-gradient(to bottom, #050b14, transparent);">
        <div class="container position-relative z-2">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 frozen-text fw-bold mb-3" data-text="THE ANCIENT FORUM">THE ANCIENT FORUM</h1>
                    <p class="text-ice lead" style="text-shadow: 0 0 10px rgba(0,217,255,0.3);">Share your legacy. Builds, strategies, and lore.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    @auth
                        <a href="{{ route('community.create') }}" class="btn btn-dark-neon px-4 py-2 fw-bold d-inline-flex align-items-center justify-content-center">
                            <span class="me-2 fs-5">+</span> CREATE THREAD
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-info px-4 py-2">Login to Post</a>
                    @endauth
                </div>
            </div>
        </div>
        <div class="position-absolute bottom-0 start-0 w-100 border-bottom border-secondary border-opacity-25"></div>
    </div>

    <div class="container fade-in-anim">
        <div class="row g-4">
            
            <div class="col-lg-3">
                <div class="sticky-top" style="top: 100px;">
                    <form action="{{ route('community.index') }}" method="GET" class="mb-4">
                        <div class="input-group border border-secondary rounded overflow-hidden">
                            <span class="input-group-text bg-black border-0 text-secondary pe-2">🔍</span>
                            <input type="text" name="search" class="form-control bg-black border-0 text-white shadow-none ps-1" placeholder="Search topics..." value="{{ request('search') }}">
                        </div>
                    </form>

                    <div class="p-3 bg-black border border-secondary rounded mb-4 shadow-lg">
                        <h6 class="font-cinzel text-ice mb-3 border-bottom border-secondary pb-2 opacity-75">SORT BY</h6>
                        <div class="btn-group w-100 mb-4">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" 
                               class="btn btn-sm {{ request('sort') != 'popular' ? 'btn-ice-active' : 'btn-outline-secondary text-secondary' }}">Latest</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" 
                               class="btn btn-sm {{ request('sort') == 'popular' ? 'btn-ice-active' : 'btn-outline-secondary text-secondary' }}">Top Rated</a>
                        </div>

                        <h6 class="font-cinzel text-ice mb-3 border-bottom border-secondary pb-2 opacity-75">CATEGORIES</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('community.index') }}" 
                               class="btn btn-sm text-start {{ !request('category') ? 'btn-ice-active' : 'btn-outline-secondary text-secondary' }}">
                                🌍 All Topics
                            </a>
                            <a href="{{ route('community.index', ['category' => 'hero']) }}" 
                               class="btn btn-sm text-start {{ request('category') == 'hero' ? 'btn-ice-active' : 'btn-outline-secondary text-secondary' }}">
                                🦸 Hero Strategy
                            </a>
                            <a href="{{ route('community.index', ['category' => 'item']) }}" 
                               class="btn btn-sm text-start {{ request('category') == 'item' ? 'btn-ice-active' : 'btn-outline-secondary text-secondary' }}">
                                ⚔️ Item Builds
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                
                @if($latestPatch)
                <div class="special-post mb-4 p-4 rounded position-relative overflow-hidden shadow-lg" 
                     style="background: rgba(0,0,0,0.8); border: 1px solid #00d9ff; box-shadow: 0 0 15px rgba(0, 217, 255, 0.15);">
                    
                    <div class="row align-items-center position-relative z-2">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-black border border-info text-info fw-bold me-2">OFFICIAL NEWS</span>
                                <span class="text-secondary small">{{ $latestPatch['date'] ?? 'Recent Update' }}</span>
                            </div>
                            <h3 class="text-white font-cinzel mb-1 text-shadow">Gameplay Update {{ str_replace('_', '.', $latestPatch['name']) }}</h3>
                            <p class="text-secondary small mb-0">Check out the latest balance changes, hero updates, and item tweaks.</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('patch.show', $latestPatch['name']) }}" class="btn btn-outline-info rounded-pill px-4 btn-sm fw-bold hover-glow-text">
                                Read Patch Notes &rarr;
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @forelse($posts as $post)
                    <div class="post-card bg-black border border-secondary rounded mb-3 p-4 position-relative hover-glow transition-all">
                        
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $post->user->profile_url }}" 
                                     width="40" height="40" class="rounded-circle border border-secondary object-fit-cover shadow">
                                
                                <div>
                                    <h6 class="text-white mb-0 fw-bold">{{ $post->user->name }}</h6>
                                    <small class="text-secondary" style="font-size: 11px;">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            
                            @if($post->hero)
                                <span class="badge bg-dark border border-secondary text-white px-3 py-2">
                                    <img src="{{ $post->hero->icon_url }}" width="18" class="me-1 rounded-circle"> {{ $post->hero->name_localized }}
                                </span>
                            @elseif($post->item)
                                <span class="badge bg-dark border border-warning text-warning px-3 py-2">
                                    <img src="{{ $post->item->img_url }}" width="22" class="me-1 border border-warning rounded"> {{ $post->item->dname }}
                                </span>
                            @else
                                <span class="badge bg-dark border border-info text-info px-3 py-2">General</span>
                            @endif
                        </div>

                        <div class="ps-md-5 ms-md-2">
                            <a href="{{ route('community.show', $post->slug) }}" class="text-decoration-none">
                                <h3 class="text-white mb-2 font-cinzel hover-link">{{ $post->title }}</h3>
                            </a>
                            
                            <p class="text-secondary mb-3 small">{{ Str::limit($post->body, 180) }}</p>
                            
                            @if($post->image_path)
                                <div class="mb-3 rounded overflow-hidden border border-secondary border-opacity-50 position-relative" style="height: 200px;">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" class="w-100 h-100 object-fit-cover opacity-75">
                                </div>
                            @endif

                            <div class="d-flex align-items-center gap-3 pt-2 border-top border-secondary border-opacity-25 mt-3">
                                <form action="{{ route('community.like', $post->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-dark border border-secondary text-info rounded-pill px-3 d-flex align-items-center gap-2 hover-upvote">
                                        <span>▲</span> <span class="fw-bold">{{ $post->likes_count }}</span>
                                    </button>
                                </form>

                                <a href="{{ route('community.show', $post->slug) }}" class="btn btn-sm btn-dark border border-secondary text-secondary rounded-pill px-3 d-flex align-items-center gap-2 hover-comment">
                                    <span>💬</span> <span class="fw-bold">{{ $post->comments_count }}</span> Comments
                                </a>

                                <span class="ms-auto text-secondary small">👁️ {{ $post->views }}</span>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="text-center py-5 border border-dashed border-secondary rounded">
                        <h3 class="text-muted font-cinzel">Silence in the library...</h3>
                        <p class="text-secondary">Be the first to start a discussion.</p>
                    </div>
                @endforelse

                <div class="mt-5">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Tombol Neon (Background Hitam, Border Biru) */
        .btn-dark-neon {
            background-color: rgba(5, 11, 20, 0.8);
            border: 1px solid #00d9ff;
            color: #00d9ff;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 217, 255, 0.1);
        }
        .btn-dark-neon:hover {
            background-color: #00d9ff;
            color: #000;
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.5);
        }

        /* Tombol Filter Aktif */
        .btn-ice-active {
            background-color: rgba(0, 217, 255, 0.15) !important;
            border-color: #00d9ff !important;
            color: #00d9ff !important;
            font-weight: bold;
        }

        /* Special Post (Featured) */
        .special-post {
            background: rgba(0,0,0,0.6);
            transition: transform 0.3s;
        }
        .special-post:hover {
            border-color: #00d9ff !important;
            transform: translateY(-2px);
        }

        /* Hover Effects */
        .hover-link:hover { color: #00d9ff !important; text-decoration: underline; }
        .hover-upvote:hover { border-color: #00d9ff !important; background: rgba(0, 217, 255, 0.15); color: #00d9ff !important; }
        .hover-comment:hover { border-color: white !important; background: rgba(255, 255, 255, 0.1); color: white !important; }
        .hover-glow:hover { border-color: #00d9ff !important; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5); }
    </style>
</x-layout>