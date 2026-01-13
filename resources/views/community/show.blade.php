<x-layout>
    <x-slot:title>{{ $post->title }} - Community</x-slot>

    <div class="container py-5 fade-in-anim">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <a href="{{ route('community.index') }}" class="btn btn-sm btn-outline-secondary mb-4">&larr; Back to Community</a>

                <div class="bg-black border border-info rounded p-4 mb-5 position-relative shadow-lg">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ $post->user->profile_url }}" 
                             width="50" height="50" class="rounded-circle border border-info p-1 bg-dark object-fit-cover">
                        <div>
                            <h5 class="text-white mb-0 font-cinzel">{{ $post->user->name }}</h5>
                            <small class="text-secondary">Posted {{ $post->created_at->format('d M Y, H:i') }}</small>
                        </div>
                        @if($post->hero)
                            <div class="ms-auto text-end">
                                <img src="{{ $post->hero->img_url }}" width="60" class="rounded border border-secondary">
                            </div>
                        @endif
                    </div>

                    <h2 class="text-white fw-bold mb-4 frozen-text">{{ $post->title }}</h2>

                    <div class="text-light mb-4" style="white-space: pre-wrap; line-height: 1.8;">{{ $post->body }}</div>

                    @if($post->image_path)
                        <div class="mb-4 text-center">
                            <img src="{{ asset('storage/' . $post->image_path) }}" class="img-fluid rounded border border-secondary shadow">
                        </div>
                    @endif

                    @if(isset($build) && $build)
                    <div class="mt-4 mb-4 p-3 border border-secondary border-opacity-50 rounded bg-dark bg-opacity-25">
                        <h5 class="text-info font-cinzel mb-3 border-bottom border-secondary pb-2">RECOMMENDED BUILD</h5>
                        <div class="row g-4">
                            @foreach(['early_game' => 'EARLY', 'mid_game' => 'MID', 'late_game' => 'LATE', 'situational' => 'SITUATIONAL'] as $phaseKey => $phaseLabel)
                                @if(!empty($build->$phaseKey))
                                <div class="col-md-3">
                                    <h6 class="text-secondary x-small mb-2 fw-bold text-uppercase">{{ $phaseLabel }}</h6>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($build->$phaseKey as $itemId)
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
                    @endif
                    <hr class="border-secondary border-opacity-25">

                    <div class="d-flex align-items-center gap-4">
                        <form action="{{ route('community.like', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn {{ $isLiked ? 'btn-info' : 'btn-outline-secondary' }} rounded-pill px-4 fw-bold">
                                ▲ UPVOTE <span class="ms-2 border-start border-dark ps-2">{{ $post->likes_count }}</span>
                            </button>
                        </form>
                        <div class="text-secondary">💬 {{ $post->comments_count }} Comments</div>
                    </div>
                </div>

                <div class="mb-5">
                    <h4 class="text-white font-cinzel mb-4">DISCUSSION</h4>

                    @auth
                        <form action="{{ route('community.comment.store', $post->id) }}" method="POST" class="mb-5">
                            @csrf
                            <div class="d-flex gap-3">
                                <img src="{{ Auth::user()->profile_url }}" 
                                     width="40" height="40" class="rounded-circle border border-secondary object-fit-cover">
                                <div class="flex-grow-1">
                                    <textarea name="body" class="form-control bg-dark border-secondary text-white mb-2" rows="3" placeholder="Join the discussion..."></textarea>
                                    <button type="submit" class="btn btn-sm btn-info fw-bold">Post Comment</button>
                                </div>
                            </div>
                        </form>
                    @endauth

                    <div class="d-flex flex-column gap-3">
                        @forelse($post->comments as $comment)
                            <div class="p-3 bg-black border border-secondary border-opacity-50 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $comment->user->profile_url }}" 
                                             width="30" height="30" class="rounded-circle border border-secondary object-fit-cover">
                                        <span class="text-white fw-bold small">{{ $comment->user->name }}</span>
                                        <span class="text-secondary x-small">&bull; {{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <p class="text-light small mb-0 ps-5">{{ $comment->body }}</p>
                            </div>
                        @empty
                            <p class="text-muted text-center">No comments yet. Be the first!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>