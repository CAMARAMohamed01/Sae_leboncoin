<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\CompteUtilisateur;

class HashExistingPasswords extends Command
{
    protected $signature = 'user:hash-passwords';
    protected $description = 'Hache tous les mots de passe en clair existants dans la BDD';

    public function handle()
    {
        $this->info('Démarrage du hachage des mots de passe...');

        // On récupère tous les utilisateurs
        // Note: On utilise DB::table pour éviter que le Cast du modèle ne double-hache si on utilisait Eloquent
        $users = DB::table('compteutilisateur')->get();

        $bar = $this->output->createProgressBar(count($users));

        foreach ($users as $user) {
            // Vérifie si le mot de passe est déjà un hash (commence par $2y$...)
            // Sinon, on le hache
            if (!Hash::info($user->motdepasse)['algo']) {
                
                $hashedPassword = Hash::make($user->motdepasse);

                // 1. Mise à jour de la table principale
                DB::table('compteutilisateur')
                    ->where('idutilisateur', $user->idutilisateur)
                    ->update(['motdepasse' => $hashedPassword]);

                // 2. Mise à jour des tables liées (Redondance dans votre schéma)
                // Votre schéma duplique le mot de passe partout, il faut donc tout mettre à jour
                // pour garder la cohérence, même si seul compteutilisateur sert au login.
                
                DB::table('particulier')
                    ->where('idutilisateur', $user->idutilisateur)
                    ->update(['motdepasse' => $hashedPassword]);
                
                DB::table('proprietaire')
                    ->where('idutilisateur', $user->idutilisateur)
                    ->update(['motdepasse' => $hashedPassword]);

                DB::table('locataire')
                    ->where('idutilisateur', $user->idutilisateur)
                    ->update(['motdepasse' => $hashedPassword]);

                DB::table('professionnel')
                    ->where('idutilisateur', $user->idutilisateur)
                    ->update(['motdepasse' => $hashedPassword]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Terminé ! Tous les mots de passe sont sécurisés.');
    }
}