<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Gunakan paginate(5) alih-alih get() atau take(5)
        $myPosts = $user->posts()
                        ->with(['hero', 'item'])
                        ->withCount('likes')
                        ->latest()
                        ->paginate(5, ['*'], 'posts_page'); 
                        // Kita beri nama 'posts_page' agar tidak bentrok jika ada pagination lain

        $myComments = $user->comments()
                           ->with('post')
                           ->latest()
                           ->paginate(5, ['*'], 'comments_page');

        return view('dashboard', compact('user', 'myPosts', 'myComments'));
    }
}