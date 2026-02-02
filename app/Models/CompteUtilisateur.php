<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CompteUtilisateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'compteutilisateur';
    protected $primaryKey = 'idutilisateur';
    public $timestamps = false;

    protected $fillable = [
        'telutilisateur',
        'motdepasse',
        'emailutilisateur',
        'solde',
        'statut_rgpd',
        'idphoto',
        'idadresse',
        'telephone_verifie',
        'role',
        'date_creation',          
        'date_derniere_connexion' 
    ];
/// début hashage
    protected $hidden = [
        'motdepasse',
    ];

    protected $casts = [
        'motdepasse' => 'hashed',
        'statut_rgpd' => 'boolean',
        'solde' => 'decimal:2',
        'telephone_verifie' => 'boolean',
        'date_creation' => 'date',
        'date_derniere_connexion' => 'date',
    ];


    public function isDPO() { return $this->role === 'dpo' || $this->role === 'admin'; }

    public function isServiceImmobilier()
    {
        return $this->role === 'service immobilier' || $this->role === 'admin';
    }
    public function isServiceInscription()
    {
        return $this->role === 'service inscription' || $this->role === 'admin';
    }
    
    public function isServiceAnnonce()
    {
        
        return $this->role === 'service petit annonce' || $this->role === 'admin';
    }
     
    public function isServiceLocation()
    {
        return $this->role === 'service location' || $this->role === 'admin';
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isServiceJuridique()
    {
        return $this->role === 'service juridique' || $this->role === 'admin';
    }
    public function getAuthPassword()
    {
        return $this->motdepasse;
    }
   



    
    public function particulier()
    {
        return $this->hasOne(Particulier::class, 'idutilisateur', 'idutilisateur');
    }

    public function professionnel()  {
        return $this->hasOne(Professionnel::class, 'idutilisateur', 'idutilisateur');
    }
    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse', 'idadresse');
    }

    
    public function identite()
    {
        return $this->hasOne(Identite::class, 'idutilisateur', 'idutilisateur');
    }

    
    public function photo()
    {
        return $this->belongsTo(Photo::class, 'idphoto', 'idphoto');
    }
    public function getNomAffichageAttribute()
    {
        if ($this->particulier) {
            return $this->particulier->prenomparticulier . ' ' . $this->particulier->nomparticulier;
        }
        
        if ($this->professionnel) {
            return $this->professionnel->nomprofessionnel . ' (Pro)';
        }

        return 'Utilisateur Leboncoin';
    }
     
    public function favoris()
    {
        
        return $this->belongsToMany(Annonce::class, 'mettre_au_favoris', 'idutilisateur', 'idannonce');
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'idutilisateur', 'idutilisateur');
    }

    
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'idutilisateur', 'idutilisateur');
    }
    ///
    public function rechercher()  {
        return $this->hasMany(Annonce::class, 'idannonce', 'idannonce');
    }

    public function rechercherpersonnenom(){
        return $this->hasMany(Rechercher::class, 'idutilisateur', 'idutilisateur');
    
    }
    public function rechercherpersonne(){
        return $this->hasOne(Annonce::class, 'idutilisateur', 'idutilisateur');
    }
    public function aDesActivites()
    {
        return $this->annonces()->exists() || $this->reservations()->exists();
    }

    public function anonymiser()
    {

        $this->emailutilisateur = 'anonyme_' . $this->idutilisateur . '@deleted.local';
        $this->telutilisateur = '0000000000';
        $this->motdepasse = 'DELETED';
        $this->statut_rgpd = false; 
        $this->idphoto = null; 
        $this->role = 'utilisateur supprimé';
        $this->telephone_verifie = false;
        $this->save();

        if ($this->particulier) {
            $this->particulier->update([
                'nomparticulier' => 'Anonyme',
                'prenomparticulier' => 'Utilisateur',
                'telutilisateur' => '0000000000',
                'datenaissance' => '1900-01-01', 
            ]);
        }

        if ($this->professionnel) {
            $this->professionnel->update([
                'nomprofessionnel' => 'Société Anonyme',
                'numerosiret' => '00000000000000',
            ]);
        }

        if ($this->adresse) {
            $this->idadresse = null;
            $this->save();
        }
        if ($this->identite) {
            $this->identite->delete();
        }
    }

    public function supprimerTotalement()
    {
        \Illuminate\Support\Facades\DB::transaction(function () {
            
            if ($this->particulier) $this->particulier->delete();
            if ($this->professionnel) $this->professionnel->delete();
            if ($this->locataire) $this->locataire->delete();
            if ($this->identite) $this->identite->delete();

            if ($this->annonces) {
                foreach ($this->annonces as $annonce) {
                    $annonce->photos()->delete();       
                    $annonce->prixPeriodes()->delete(); 
                    $annonce->equipements()->detach();  
                    $annonce->services()->detach();     
                    $annonce->avis()->delete();         
                    
                    \App\Models\Reservation::where('idannonce', $annonce->idannonce)->delete();
                    
                    $annonce->delete();
                }
            }

            if ($this->reservations) {
                foreach ($this->reservations as $res) {
                    \App\Models\Reglement::where('idreservation', $res->idreservation)->delete();
                    $res->delete();
                }
            }

            \App\Models\Avis::where('idutilisateur', $this->idutilisateur)->delete();

            $this->favoris()->detach();

            \App\Models\DemandeSuppression::where('idutilisateur', $this->idutilisateur)->delete();

            $this->delete();

             if ($this->idadresse) {
                 $adresse = \App\Models\Adresse::find($this->idadresse);
             }
        });
    }
}