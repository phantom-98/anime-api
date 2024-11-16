<?php

// app/Console/Commands/FetchAnimeData.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Anime;

class FetchAnimeData extends Command
{
    protected $signature = 'anime:fetch {limit=100}';
    protected $description = 'Fetch popular anime from Jikan API';

    public function handle()
    {
        $limit = (int) $this->argument('limit'); // Total number of records to fetch
        $batchSize = 25; // Jikan API max limit
        $totalFetched = 0; // Counter for fetched records

        // Ensure the limit is valid
        if ($limit > 100 || $limit <= 0) {
            $this->error('The limit must be between 1 and 100.');
            return;
        }

        // Loop through batches to fetch data
        for ($offset = 0; $offset < $limit; $offset += $batchSize) {
            $currentBatchSize = min($batchSize, $limit - $totalFetched); // Calculate batch size

            try {
                $response = Http::get("https://api.jikan.moe/v4/anime", [
                    'limit' => $currentBatchSize,
                    'offset' => $offset,
                ]);

                if ($response->failed()) {
                    $this->error("Failed to fetch data at offset $offset. Error: {$response->body()}");
                    break;
                }

                $animes = $response->json()['data'];

                foreach ($animes as $anime) {
                    Anime::updateOrCreate(
                        ['mal_id' => $anime['mal_id']],
                        [
                            'title' => $anime['title'] ?? 'Unknown Title',
                            'slug' => strtolower(str_replace(' ', '-', $anime['title'] ?? 'unknown-title')),
                            'synopsis' => $anime['synopsis'] ?? 'No synopsis available',
                            'image_url' => $anime['images']['jpg']['image_url'] ?? null,
                        ]
                    );
                }

                $totalFetched += count($animes);
                $this->info("Fetching offset {$offset} with batch size {$currentBatchSize}...");

                $this->info("Fetched {$totalFetched}/{$limit} records...");

            } catch (\Exception $e) {
                $this->error("An error occurred: {$e->getMessage()}");
                break;
            }

            // Stop if there are no more records to fetch
            if (count($animes) < $currentBatchSize) {
                break;
            }
        }

        $this->info("Successfully fetched {$totalFetched} anime records.");
    }
}
