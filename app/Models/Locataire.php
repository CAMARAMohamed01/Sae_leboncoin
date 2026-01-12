<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CompteUtilisateur;
use App\Models\Incident;
use App\Models\Reservation;

class Locataire extends Model
{
    protected $table = 'locataire';
    protected $primaryKey = 'idlocateur'; 
    public $timestamps = false;

    protected $fillable = [
        'idutilisateur',
        'idparticulier',
        'nomlocateur',
        'prenomlocateur',
        'telutilisateur',
        'solde',
        'datenaissance',
        'motdepasse',
        'statut_rgpd'
    ];

    public function compte()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }


    public function incident()
    {
        
        return $this->hasMany(Incident::class, 'idincident', 'idincident');
    }
    public function reservation()
    {
        
        return $this->hasMany(Reservation::class, 'idreservation', 'idreservation');
    }
}
