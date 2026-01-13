<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Hero;
use App\Models\Ability;

class AbilitySeeder extends Seeder
{
    public function run(): void
    {
        $pathAbilities = storage_path('app/json/abilities.json');
        $pathHeroAbilities = storage_path('app/json/hero_abilities.json');

        if (!File::exists($pathAbilities) || !File::exists($pathHeroAbilities)) {
            echo "ERROR: JSON files not found in storage/app/json/\n";
            return;
        }

        $allAbilities = json_decode(File::get($pathAbilities), true);
        $heroAbilitiesMap = json_decode(File::get($pathHeroAbilities), true);
        
        $heroes = Hero::all();

        foreach ($heroes as $hero) {
            $heroKey = $hero->code_name; // npc_dota_hero_...
            
            // Check mapping
            $abilitiesList = $heroAbilitiesMap[$heroKey]['abilities'] ?? ($heroAbilitiesMap[$heroKey] ?? []);

            foreach ($abilitiesList as $skillName) {
                if ($skillName === 'generic_hidden' || !isset($allAbilities[$skillName])) continue;
                
                $skillData = $allAbilities[$skillName];
                
                // Video Logic
                $shortName = str_replace('npc_dota_hero_', '', $hero->code_name);
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
        echo "✅ Abilities Updated.\n";
    }
}