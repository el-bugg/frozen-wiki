<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Hero;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        // Logic Filter & Sorting
        $query = Post::with(['user', 'hero', 'item'])->withCount(['comments', 'likes']);

        // Filter Kategori
        if ($request->has('category')) {
            if ($request->category == 'general') $query->whereNull('hero_id')->whereNull('item_id');
            if ($request->category == 'hero') $query->whereNotNull('hero_id');
            if ($request->category == 'item') $query->whereNotNull('item_id');
        }

        // Filter Hero Spesifik
        if ($request->has('hero_id')) {
            $query->where('hero_id', $request->hero_id);
        }

        // Sorting (Latest vs Popular/Upvoted)
        if ($request->sort == 'popular') {
            $query->orderBy('likes_count', 'desc');
        } else {
            $query->latest();
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->paginate(10)->withQueryString();

        // Data untuk Sidebar (Latest News / Patch)
        $latestPatch = null;
        $patchPath = storage_path('app/json/patch.json'); 
        if (File::exists($patchPath)) {
            $patches = json_decode(File::get($patchPath), true);
            if (is_array($patches) && count($patches) > 0) $latestPatch = end($patches); 
        }

        // Data Heroes untuk filter dropdown
        $heroes = Hero::orderBy('name_localized')->get(['id', 'name_localized']);

        return view('community.index', compact('posts', 'latestPatch', 'heroes'));
    }

    public function create()
    {
        $heroes = Hero::orderBy('name_localized')->get(['id', 'name_localized']);
        // Ambil semua item untuk picker
        $items = Item::orderBy('dname')->get(['id', 'dname', 'img_url']);
        
        return view('community.create', compact('heroes', 'items'));
    }

    // Update method store
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'type' => 'required|in:general,hero,item',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // 1. Buat Postingan Forum (agar muncul di Community Feed)
        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->slug = Str::slug($request->title) . '-' . Str::random(6);

        if ($request->type == 'hero') $post->hero_id = $request->related_id;
        if ($request->type == 'item') $post->item_id = $request->related_id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image_path = $path;
        }
        $post->save();

        // 2. KHUSUS HERO STRATEGY: Simpan juga sebagai Hero Build
        if ($request->type == 'hero' && $request->related_id) {
            // Cek apakah user mengisi setidaknya satu item
            if($request->early_game || $request->mid_game || $request->late_game) {
                \App\Models\HeroBuild::create([
                    'user_id' => Auth::id(),
                    'hero_id' => $request->related_id,
                    'title'   => $request->title, // Judul build sama dengan judul post
                    'early_game'  => $request->early_game ?? [],  // Array ID item
                    'mid_game'    => $request->mid_game ?? [],
                    'late_game'   => $request->late_game ?? [],
                    'situational' => $request->situational ?? [],
                ]);
            }
        }

        return redirect()->route('community.index')->with('success', 'Thread & Build created successfully!');
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
                    ->with(['user', 'hero', 'item', 'comments.user'])
                    ->withCount('likes')
                    ->firstOrFail();
        
        $isLiked = false;
        if(Auth::check()) {
            $isLiked = $post->likes()->where('user_id', Auth::id())->exists();
        }

        // --- LOGIC BARU: AMBIL BUILD ---
        $build = null;
        $allItems = []; // Untuk gambar item
        
        // Jika post ini bertipe Hero Strategy, coba cari build-nya
        if ($post->hero_id) {
            $build = \App\Models\HeroBuild::where('user_id', $post->post_user_id ?? $post->user_id)
                        ->where('hero_id', $post->hero_id)
                        ->where('title', $post->title) // Asumsi judul sama
                        ->latest()
                        ->first();
            
            // Jika ketemu build, load semua item untuk mapping gambar
            if ($build) {
                $allItems = Item::all()->keyBy('id');
            }
        }

        return view('community.show', compact('post', 'isLiked', 'build', 'allItems'));
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate(['body' => 'required|max:1000']);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function likePost(Post $post)
    {
        $existingLike = $post->likes()->where('user_id', Auth::id())->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            $post->likes()->create(['user_id' => Auth::id()]);
        }

        return back();
    }
}