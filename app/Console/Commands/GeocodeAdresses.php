<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Adresse;
use Illuminate\Support\Facades\Http;

class GeocodeAdresses extends Command
{
    protected $signature = 'geocode:adresses';
    protected $description = 'Met à jour les coordonnées GPS des adresses via l\'API Gouv';

    public function handle()
    {
        $total = Adresse::count();
        $this->info("📊 Total adresses dans la table : " . $total);

        $adresses = Adresse::with('ville')
                    ->whereNull('latitude')
                    ->orWhereNull('longitude')
                    ->get();

        $count = $adresses->count();
        
        if ($count === 0) {
            $this->warn("⚠️ Aucune adresse avec latitude NULL trouvée !");
            $this->info("🔄 Mode Force : On tente de mettre à jour les 5 premières adresses...");
            $adresses = Adresse::with('ville')->take(5)->get();
        } else {
            $this->info("magnifier: Trouvé $count adresses à géocoder.");
        }

        foreach ($adresses as $adresse) {
            
            $numero = $adresse->voie ?? '';
            $rue = $adresse->nomrue ?? '';
            
            if (!$adresse->ville) {
                $this->error("❌ Adresse ID {$adresse->idadresse} ignorée : Pas de ville liée (idville invalide ?)");
                continue;
            }

            $cp = $adresse->ville->cpville ?? '';
            $ville = $adresse->ville->nomville ?? '';

            $query = trim("$numero $rue $cp $ville");
            $this->line("📡 Recherche API pour : [$query]");

            try {
                $response = Http::timeout(5)->get('https://api-adresse.data.gouv.fr/search/', [
                    'q' => $query,
                    'limit' => 1
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (!empty($data['features'])) {
                        $coords = $data['features'][0]['geometry']['coordinates'];
                        $score = $data['features'][0]['properties']['score'];
                        
                        $adresse->longitude = $coords[0];
                        $adresse->latitude = $coords[1];
                        $adresse->save();

                        $this->info("   ✅ OK (Score: $score) -> Lat: {$coords[1]}, Lon: {$coords[0]}");
                    } else {
                        $this->warn("   ⚠️ API : Aucun résultat trouvé pour cette adresse.");
                    }
                } else {
                    $this->error("   ❌ Erreur API : " . $response->status());
                }

            } catch (\Exception $e) {
                $this->error("   🔥 Exception : " . $e->getMessage());
            }

            usleep(100000);
        }

        $this->newLine();
        $this->info("🏁 Terminé.");
    }
}