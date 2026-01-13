<x-layout>
    <x-slot:title>Dashboard - Frozen Wiki</x-slot>

    <div class="position-relative" style="height: 350px; overflow: hidden;">
        <img src="https://cdn.cloudflare.steamstatic.com/apps/dota2/images/dota_react/home/heroes_full.jpg" 
             class="w-100 h-100" 
             style="object-fit: cover; object-position: top center; opacity: 0.6;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent 60%, #050b14 100%);"></div>
    </div>

    <div class="container fade-in-anim position-relative" style="margin-top: -100px;">
        
        <div class="bg-black border border-secondary rounded p-4 mb-5 shadow-lg position-relative z-2">
            <div class="row align-items-end">
                <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                    
                    <img src="{{ $user->profile_url }}" 
                         class="rounded-circle border border-4 border-black bg-dark shadow" width="120" height="120" style="object-fit: cover;">
                
                </div>
                <div class="col-md text-center text-md-start">
                    <h2 class="text-white font-cinzel fw-bold mb-1">{{ $user->name }}</h2>
                    <p class="text-secondary small mb-2">{{ $user->email }}</p>
                    <div class="d-flex gap-2 justify-content-center justify-content-md-start">
                        <span class="badge bg-dark border border-secondary text-ice">Member since {{ $user->created_at->format('M Y') }}</span>
                        <span class="badge bg-dark border border-secondary text-warning">Posts: {{ $myPosts->count() }}</span>
                    </div>
                </div>
                <div class="col-md-auto text-center text-md-end mt-3 mt-md-0">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">Edit Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline-block ms-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-4">Log Out</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-2">
                    <h4 class="frozen-text m-0" data-text="RECENT ACTIVITY">RECENT ACTIVITY</h4>
                    <a href="{{ route('community.create') }}" class="btn btn-sm btn-info fw-bold">+ CREATE POST</a>
                </div>
                @forelse($myPosts as $post)
                    <div class="p-4 mb-3 bg-black border border-secondary rounded hover-glow position-relative transition-all">
                        <div class="d-flex justify-content-between">
                            <div class="pe-3">
                                @if($post->hero)
                                    <span class="badge bg-dark border border-secondary text-white mb-2 small"><img src="{{ $post->hero->icon_url }}" width="15"> {{ $post->hero->name_localized }}</span>
                                @elseif($post->item)
                                    <span class="badge bg-dark border border-warning text-warning mb-2 small"><img src="{{ $post->item->img_url }}" width="18"> {{ $post->item->dname }}</span>
                                @else
                                    <span class="badge bg-dark border border-info text-info mb-2 small">General</span>
                                @endif
                                <a href="{{ route('community.show', $post->slug) }}" class="text-decoration-none">
                                    <h5 class="text-white mb-1 font-cinzel">{{ $post->title }}</h5>
                                </a>
                                <p class="text-secondary small mb-0">{{ Str::limit($post->body, 100) }}</p>
                            </div>
                            <div class="text-center border-start border-secondary ps-3 d-flex flex-column justify-content-center" style="min-width: 80px;">
                                <div class="text-info fw-bold fs-5">{{ $post->likes_count }}</div>
                                <div class="text-secondary x-small">UPVOTES</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 border border-dashed border-secondary rounded opacity-50">
                        <p class="text-muted">No battle records found.</p>
                        <a href="{{ route('community.create') }}" class="text-info">Start your first discussion</a>
                    </div>
                @endforelse
            </div>
            <div class="col-lg-4">
                <h5 class="text-white font-cinzel mb-4 border-bottom border-secondary pb-2">LATEST COMMENTS</h5>
                <div class="bg-black border border-secondary rounded p-0 overflow-hidden">
                    @forelse($myComments as $comment)
                        <div class="p-3 border-bottom border-secondary border-opacity-25 hover-bg-dark">
                            <small class="text-secondary d-block mb-1">
                                On: <a href="{{ route('community.show', $comment->post->slug) }}" class="text-info text-decoration-none">{{ Str::limit($comment->post->title, 25) }}</a>
                            </small>
                            <p class="text-light small mb-0 fst-italic">"{{ Str::limit($comment->body, 50) }}"</p>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted small">No comments yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .hover-glow:hover { border-color: var(--ice-blue) !important; box-shadow: 0 0 15px rgba(0, 217, 255, 0.1); transform: translateX(5px); }
        .hover-bg-dark:hover { background: rgba(255,255,255,0.05); }
    </style>
</x-layout>