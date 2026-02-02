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
        'idutilisateur', 
        'idlocateur',    
        'iddate',        
        'dat_iddate',    
        'date_reservation',
        'nbjours',
        'nbadulte',
        'nbenfant',
        'nbanimeaux',
        'nbbebe',
        'statut_reservation' 
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'idannonce', 'idannonce');
    }

    public function locataire()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }
    public function dateDebut()
    {
        return $this->belongsTo(Dates::class, 'iddate', 'iddate');
    }

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

    public function messages()
    {
        return $this->hasMany(Message::class, 'idreservation', 'idreservation')->orderBy('dateenvoi', 'asc');
    }
 

}