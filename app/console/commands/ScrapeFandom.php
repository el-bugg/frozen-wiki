<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Hero;
use App\Models\Item;

class ScrapeFandom extends Command
{
    protected $signature = 'dota:scrape-fandom';
    protected $description = 'Scrape Fandom Guide Data';

    public function handle()
    {
        $this->info('Starting Fandom Scraper...');

        $validItems = Item::pluck('dname')->map(fn($n) => strtolower($n))->flip()->toArray();
        $heroes = Hero::whereNotNull('code_name')->get();
        $bar = $this->output->createProgressBar($heroes->count());

        foreach ($heroes as $hero) {
            $wikiName = str_replace(' ', '_', $hero->name_localized);
            
            $shortName = str_replace('npc_dota_hero_', '', $hero->code_name);
            switch ($shortName) {
                case 'nevermore': $shortName = 'shadow_fiend'; break;
                case 'rattletrap': $shortName = 'clockwerk'; break;
                case 'magnataur': $shortName = 'magnus'; break;
                case 'wisp': $shortName = 'io'; break;
                case 'obsidian_destroyer': $shortName = 'outworld_destroyer'; break;
                case 'skeleton_king': $shortName = 'wraith_king'; break;
                case 'zuus': $shortName = 'zeus'; break;
                case 'doom_bringer': $shortName = 'doom'; break;
                case 'treant': $shortName = 'treant_protector'; break;
            }
            
            $urlGuide = "https://dota2.fandom.com/wiki/{$wikiName}/Guide";
            $response = Http::get($urlGuide);
            
            $playstyle = null;
            $pros = [];
            $cons = [];

            if ($response->successful()) {
                $crawler = new Crawler($response->body());

                $crawler->filter('h2')->each(function ($node) use (&$playstyle) {
                    if (str_contains($node->text(), 'Gameplay')) {
                        $node->nextAll()->each(function($sibling) use (&$playstyle) {
                            if ($playstyle) return; 
                            if ($sibling->nodeName() === 'table') return;

                            $text = trim($sibling->text());
                            if (str_contains($text, 'Hero Talents')) return;
                            if (str_contains($text, 'Pros')) return;
                            if (str_contains($text, 'Cons')) return;

                            if (strlen($text) > 60) {
                                $playstyle = $text;
                            }
                        });
                    }
                });

                $crawler->filter('td, th')->each(function ($node) use (&$pros) {
                    if (trim($node->text()) === 'Pros') {
                        $table = $node->closest('table');
                        if ($table) {
                            $table->filter('ul')->first()->filter('li')->each(function($li) use (&$pros){
                                $pros[] = trim($li->text());
                            });
                        }
                    }
                });

                $crawler->filter('td, th')->each(function ($node) use (&$cons) {
                    if (trim($node->text()) === 'Cons') {
                        $table = $node->closest('table');
                        if ($table) {
                            $table->filter('ul')->last()->filter('li')->each(function($li) use (&$cons){
                                $cons[] = trim($li->text());
                            });
                        }
                    }
                });
            }

            $hero->update([
                'playstyle'   => $playstyle ?? $hero->playstyle,
                'pros'        => !empty($pros) ? $pros : null,
                'cons'        => !empty($cons) ? $cons : null,
            ]);

            usleep(100000); 
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }
}