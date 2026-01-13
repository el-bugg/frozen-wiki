<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Hero;
use App\Models\Item;
use App\Models\Ability;
use Illuminate\Support\Facades\DB; // Untuk Patch manual jika belum ada Model

class FrozenSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedHeroes();
        $this->seedItems();
        $this->seedAbilities();
        // Patch biasanya data statis untuk referensi, kita bisa skip insert DB
        // atau buat tabel patch jika memang mau disimpan di DB.
    }

    private function seedHeroes()
    {
        $pathHeroes = storage_path('app/json/heroes.json');
        $pathLore = storage_path('app/json/hero_lore.json');

        if (!File::exists($pathHeroes)) return;
        
        $heroesData = json_decode(File::get($pathHeroes), true);
        $loreData = File::exists($pathLore) ? json_decode(File::get($pathLore), true) : [];

        foreach ($heroesData as $h) {
            $codeName = $h['name'];
            $shortName = str_replace('npc_dota_hero_', '', $codeName);
            
            $videoName = $shortName;
            $videoMap = [
                'nevermore' => 'shadow_fiend', 'rattletrap' => 'clockwerk', 'magnataur' => 'magnus',
                'wisp' => 'io', 'obsidian_destroyer' => 'outworld_destroyer', 'skeleton_king' => 'wraith_king',
                'zuus' => 'zeus', 'doom_bringer' => 'doom', 'treant' => 'treant_protector',
                'life_stealer' => 'lifestealer', 'windrunner' => 'windranger', 'vengefulspirit' => 'vengeful_spirit',
                'necrolyte' => 'necrophos'
            ];
            if (isset($videoMap[$shortName])) $videoName = $videoMap[$shortName];

            Hero::updateOrCreate(
                ['dota_id' => $h['id']],
                [
                    'code_name' => $codeName,
                    'name_localized' => $h['localized_name'],
                    'primary_attr' => $h['primary_attr'],
                    'attack_type' => $h['attack_type'],
                    'roles' => $h['roles'],
                    'img_url' => 'https://cdn.cloudflare.steamstatic.com' . $h['img'],
                    'icon_url' => 'https://cdn.cloudflare.steamstatic.com' . $h['icon'],
                    'video_url' => "https://cdn.steamstatic.com/apps/dota2/videos/dota_react/heroes/renders/{$videoName}.webm",
                    'lore' => $loreData[$shortName] ?? ($loreData[$codeName] ?? null),

                    // DATA STATS LENGKAP (Sesuai JSON OpenDota)
                    'base_health' => $h['base_health'] ?? 200,
                    'base_health_regen' => $h['base_health_regen'] ?? 0,
                    'base_mana' => $h['base_mana'] ?? 75,
                    'base_mana_regen' => $h['base_mana_regen'] ?? 0,
                    'base_str' => $h['base_str'] ?? 0,
                    'base_agi' => $h['base_agi'] ?? 0,
                    'base_int' => $h['base_int'] ?? 0,
                    'str_gain' => $h['str_gain'] ?? 0,
                    'agi_gain' => $h['agi_gain'] ?? 0,
                    'int_gain' => $h['int_gain'] ?? 0,
                    'attack_range' => $h['attack_range'] ?? 0,
                    'move_speed' => $h['move_speed'] ?? 0,
                    'base_armor' => $h['base_armor'] ?? 0,
                    'base_attack_min' => $h['base_attack_min'] ?? 0,
                    'base_attack_max' => $h['base_attack_max'] ?? 0,
                ]
            );
        }
    }

    private function seedItems()
    {
        $path = storage_path('app/json/items.json');
        if (!File::exists($path)) return;

        $data = json_decode(File::get($path), true);

        foreach ($data as $key => $i) {
            if (empty($i['dname'])) continue;

            $stats = [];
            if (isset($i['attrib'])) {
                foreach ($i['attrib'] as $attr) {
                    $rawKey = $attr['header'] ?? ($attr['key'] ?? '');
                    
                    // CLEANING: Hapus underscore, Ubah jadi Title Case
                    // "bonus_movement_speed" -> "Bonus Movement Speed"
                    if ($rawKey) {
                        $cleanKey = ucwords(str_replace('_', ' ', $rawKey));
                        
                        $val = $attr['value'] ?? '';
                        if (isset($attr['footer'])) $val .= $attr['footer']; // Tambah %
                        
                        $stats[$cleanKey] = $val;
                    }
                }
            }

            Item::updateOrCreate(
                ['key_name' => $key],
                [
                    'dota_id' => $i['id'] ?? null,
                    'dname' => $i['dname'],
                    'cost' => $i['cost'] ?? 0,
                    'desc' => is_array($i['hint'] ?? null) ? implode("\n", $i['hint']) : ($i['hint'] ?? null),
                    'lore' => $i['lore'] ?? null,
                    'img_url' => 'https://cdn.cloudflare.steamstatic.com' . $i['img'],
                    'stats' => !empty($stats) ? $stats : null,
                    'components' => $i['components'] ?? null,
                    'recipe_cost' => str_contains($key, 'recipe') ? 1 : 0,
                    
                    // Masukkan CD dan Mana ke kolom terpisah
                    'cooldown' => $i['cd'] ?? null,
                    'mana_cost' => $i['mc'] ?? null,
                ]
            );
        }
    }

    private function seedAbilities()
    {
        $pathAbilities = storage_path('app/json/abilities.json');
        $pathHeroAbilities = storage_path('app/json/hero_abilities.json');

        if (!File::exists($pathAbilities) || !File::exists($pathHeroAbilities)) return;

        $allAbilities = json_decode(File::get($pathAbilities), true);
        $heroAbilitiesMap = json_decode(File::get($pathHeroAbilities), true);
        
        $heroes = Hero::all();

        foreach ($heroes as $hero) {
            $heroKey = $hero->code_name;
            $abilitiesList = $heroAbilitiesMap[$heroKey]['abilities'] ?? ($heroAbilitiesMap[$heroKey] ?? []);

            foreach ($abilitiesList as $skillName) {
                if ($skillName === 'generic_hidden' || !isset($allAbilities[$skillName])) continue;
                
                $skillData = $allAbilities[$skillName];
                
                // Logic Video Skill Valve
                $shortName = str_replace('npc_dota_hero_', '', $hero->code_name);
                // Mapping nama untuk video skill
                $videoMap = [
                    'nevermore' => 'shadow_fiend', 'rattletrap' => 'clockwerk', 'magnataur' => 'magnus',
                    'wisp' => 'io', 'obsidian_destroyer' => 'outworld_destroyer', 'skeleton_king' => 'wraith_king',
                    'zuus' => 'zeus', 'doom_bringer' => 'doom', 'treant' => 'treant_protector',
                    'life_stealer' => 'lifestealer', 'windrunner' => 'windranger', 'vengefulspirit' => 'vengeful_spirit',
                    'necrolyte' => 'necrophos'
                ];
                if(isset($videoMap[$shortName])) $shortName = $videoMap[$shortName];

                $videoUrl = "https://cdn.steamstatic.com/apps/dota2/videos/dota_react/abilities/{$shortName}/{$skillName}.mp4";

                Ability::updateOrCreate(
                    ['hero_id' => $hero->id, 'name' => $skillName],
                    [
                        'dname' => $skillData['dname'] ?? $skillName,
                        'desc' => $skillData['desc'] ?? null,
                        'img_url' => isset($skillData['img']) ? 'https://cdn.cloudflare.steamstatic.com' . $skillData['img'] : null,
                        'behavior' => is_array($skillData['behavior'] ?? null) ? implode(', ', $skillData['behavior']) : ($skillData['behavior'] ?? null),
                        'mana_cost' => $skillData['mc'] ?? null,
                        'cooldown' => $skillData['cd'] ?? null,
                        'video_url' => $videoUrl
                    ]
                );
            }
        }
    }
}