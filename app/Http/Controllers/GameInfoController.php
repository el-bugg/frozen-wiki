<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Models\Hero;
use App\Models\Item;
use Carbon\Carbon;

class GameInfoController extends Controller
{
    public function showPatch($version)
    {
        // Path ke kedua file JSON
        $pathNotes = storage_path('app/json/patchnotes.json');
        $pathList = storage_path('app/json/patch.json');
        
        if (!File::exists($pathNotes) || !File::exists($pathList)) {
            abort(404, 'Database Patch tidak ditemukan.');
        }

        // 1. Ambil Detail Perubahan (Content)
        $allNotes = json_decode(File::get($pathNotes), true);
        $patchContent = $allNotes[$version] ?? null;

        // Fallback: Coba ganti titik dengan underscore (7.35 -> 7_35)
        if (!$patchContent) {
            $underscoreVersion = str_replace('.', '_', $version);
            $patchContent = $allNotes[$underscoreVersion] ?? null;
            if ($patchContent) $version = $underscoreVersion; // Update version key jika ketemu
        }

        if (!$patchContent) {
            abort(404, "Patch note versi $version tidak ditemukan.");
        }

        // 2. Ambil Metadata (Tanggal) dari patch.json
        $patchList = json_decode(File::get($pathList), true);
        $patchMeta = null;

        // patch.json berbentuk Array, kita harus cari yang name-nya cocok
        foreach ($patchList as $p) {
            if ($p['name'] == $version) {
                $patchMeta = $p;
                break;
            }
        }

        // Format Tanggal (jika ada)
        $releaseDate = null;
        if ($patchMeta && isset($patchMeta['date'])) {
            $releaseDate = Carbon::parse($patchMeta['date'])->format('d F Y');
        }

        return view('game.patch', compact('patchContent', 'version', 'releaseDate'));
    }

    public function showMatch($match_id)
    {
        $response = Http::get("https://api.opendota.com/api/matches/{$match_id}");

        if ($response->failed()) {
            abort(404);
        }

        $match = $response->json();
        
        $heroes = Hero::all()->keyBy('dota_id'); 
        $items = Item::whereNotNull('dota_id')->get()->keyBy('dota_id');

        return view('game.match', compact('match', 'heroes', 'items'));
    }
}