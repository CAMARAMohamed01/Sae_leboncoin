<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CompteUtilisateur;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PurgeInactifs extends Command
{
    /**
     * La signature de la commande.
     * 
     */
    protected $signature = 'rgpd:purge';

    /**
     * Description de la commande.
     */
    protected $description = 'Anonymise les comptes inactifs depuis plus de 3 ans (RGPD)';

    /**
     * Exécution de la commande.
     */
    public function handle()
    {
        
        $dateLimite = Carbon::now()->subYears(3);

        $this->info("Recherche des comptes inactifs avant le : " . $dateLimite->format('d/m/Y'));
        $usersAAnonymiser = CompteUtilisateur::where('date_derniere_connexion', '<', $dateLimite)
            ->where('statut_rgpd', true)
            ->whereNotIn('role', ['admin', 'dpo'])
            ->get();

        $count = $usersAAnonymiser->count();

        if ($count === 0) {
            $this->info("Aucun compte à purger aujourd'hui.");
            return;
        }

        $this->info("Nombre de comptes inactifs à anonymiser : $count");

        // if ($this->confirm("Voulez-vous vraiment anonymiser $count comptes inactifs ?")) {
            $bar = $this->output->createProgressBar($count);
            $bar->start();

            foreach ($usersAAnonymiser as $user) {
                try {
                    // Appel de la méthode d'anonymisation du modèle
                    $user->anonymiser();
                    Log::info("RGPD Purge Auto : Compte ID {$user->idutilisateur} anonymisé (Inactif depuis > 3 ans).");
                } catch (\Exception $e) {
                    Log::error("Erreur Purge User {$user->idutilisateur} : " . $e->getMessage());
                }
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("$count comptes ont été anonymisés avec succès.");
        // }
    }
}