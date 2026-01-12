<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\ReservationController;


class Reservation extends Model
{
    protected $table = 'reservation';
    protected $primaryKey = 'idreservation';
    public $timestamps = false;

    protected $fillable = [
        'idannonce',
        'idutilisateur', // Locataire
        'idlocateur',    // Profil locataire
        'iddate',        // Date début
        'dat_iddate',    // Date fin
        'date_reservation', // Date de création
        'nbjours',
        'nbadulte',
        'nbenfant',
        'nbanimeaux',
        'nbbebe',
        'statut_reservation' 
    ];

    // L'annonce réservée
    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'idannonce', 'idannonce');
    }

    // 2. Le locataire (l'utilisateur qui a fait la demande)
    public function locataire()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }
    // La date de début (liée à la table dates)
    public function dateDebut()
    {
        return $this->belongsTo(Dates::class, 'iddate', 'iddate');
    }

    // La date de fin (liée à la table dates)
    public function dateFin()
    {
        return $this->belongsTo(Dates::class, 'dat_iddate', 'iddate');
    }
    public function reglements()
    {
        return $this->hasMany(Reglement::class, 'idreservation', 'idreservation');
    }

    
    public function reglement()
    {
        return $this->hasOne(Reglement::class, 'idreservation', 'idreservation')->latest('numeroreglement');
    }

    // Récupère les messages liés à cette réservation, triés du plus récent au plus ancien
    public function messages()
    {
        return $this->hasMany(Message::class, 'idreservation', 'idreservation')->orderBy('dateenvoi', 'asc');
    }

    

}