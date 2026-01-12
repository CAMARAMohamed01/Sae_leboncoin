<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\CompteUtilisateur;
use App\Models\Service;
use App\Models\Adresse;
use App\Models\Equipement; 
use App\Models\Dates;       
use App\Models\PrixPeriode; 
use App\Models\Particulier; 
use App\Models\Avis;        
use App\Models\Ville;       
use App\Models\TypeHebergement; 
use App\Models\Photo;

class Annonce extends Model
{
    use HasFactory;

    protected $table = 'annonce';
    protected $primaryKey = "idannonce";
    public $timestamps = false;

    
    
    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse', 'idadresse');
    }
    
    public function services()
    {
        return $this->belongsToMany(Service::class, 'possede', 'idannonce', 'idservice');
    }

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'idville');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'idannonce');
    }

    public function conditionHebergement()
    {
        return $this->belongsTo(ConditionHebergement::class, 'idconditionheb', 'idconditionheb');
    }
    
    public function typeHebergement()
    {
        return $this->belongsTo(TypeHebergement::class, 'idtypehebergement');
    }
    
    public function equipements()
    {
        return $this->belongsToMany(Equipement::class, 'contient', 'idannonce', 'idequipement');
    }

    public function proprietaire()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }

    public function dateEnregistrement()
    { 
        return $this->belongsTo(Dates::class, 'iddate', 'iddate');
    }

    // =========================================================
    // CORRECTION ICI : Remplacement de belongsTo par hasMany
    // =========================================================
    public function prixPeriodes()
    {
        // Une annonce A PLUSIEURS périodes de prix
        return $this->hasMany(PrixPeriode::class, 'idannonce', 'idannonce');
    }
    // =========================================================

    public function rechercherpersonne(){
        return $this->belongsTo(Particulier::class, 'idutilisateur', 'idutilisateur');
    }

    public function particulier()
    {
        return $this->belongsTo(Particulier::class, 'idannonce', 'idannonce');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'idannonce', 'idannonce');
    }

    public function avisRelations()
    {
        return $this->hasMany(Avis::class, 'idutilisateur', 'idutilisateur');
    }

    public function incident()
    {
        return $this->hasMany(Incident::class, 'idannonce', 'idannonce');
    }
    public function datenondispo(){
        return $this->hasMany(Journondisponible::class , 'idannonce','idannonce');
    }

    public function getNoteMoyenneAttributes()
    {
        $avis = $this->avisRelations;

        if ($avis->isEmpty()) {
            return 5; 
        }
        
        return $avis->avg('note');
    }

    
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'idannonce', 'idannonce');
    }

    
    public function getJoursOccupesAttribute()
    {
        $joursOccupes = [];

        // On prend les réservations qui bloquent le calendrier
        $reservations = $this->reservations()
            ->whereIn('statut_reservation', ['Acceptée', 'En attente'])
            ->with(['dateDebut', 'dateFin']) // On charge les dates liées
            ->get();

        foreach ($reservations as $resa) {
            // Sécurité si les dates sont manquantes
            if (!$resa->dateDebut || !$resa->dateFin) continue;

            $debut = \Carbon\Carbon::parse($resa->dateDebut->dateacte);
            $fin = \Carbon\Carbon::parse($resa->dateFin->dateacte);

            // On boucle du début à la fin pour ajouter chaque jour au tableau
            while ($debut->lte($fin)) {
                $joursOccupes[] = $debut->format('Y-m-d');
                $debut->addDay();
            }
        }

        return $joursOccupes;
    }


    protected $fillable = [
        'idtypehebergement',
        'idconditionheb',
        'iddate',
        'idville',
        'idadresse',
        'titreannonce',
        'descriptionannonce',
        'est_garantie'
    ];
    protected $casts = [
    'est_garantie' => 'boolean',
];
}