<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hero;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Hero of the Day
        $heroes = Hero::inRandomOrder()->take(10)->get();
        $topPicked = Hero::orderBy('id')->take(3)->get();

        // 2. Latest Patch
        $latestPatch = null;
        $patchPath = storage_path('app/json/patch.json'); 
        if (File::exists($patchPath)) {
            $patches = json_decode(File::get($patchPath), true);
            if (is_array($patches) && count($patches) > 0) $latestPatch = end($patches); 
        }

        // 3. Esports Matches (Pastikan nama variabel konsisten)
        $esportsMatches = Cache::remember('home_pro_matches', 300, function () {
            try {
                $response = Http::timeout(5)->get('https://api.opendota.com/api/proMatches');
                if ($response->successful()) {
                    return array_slice($response->json(), 0, 5);
                }
            } catch (\Exception $e) {
                return [];
            }
            return [];
        });

        // PERBAIKAN: Gunakan 'esportsMatches' di dalam compact
        return view('welcome', compact('heroes', 'topPicked', 'latestPatch', 'esportsMatches'));
    }
}