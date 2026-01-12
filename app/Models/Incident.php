<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $table = 'incident'; 
    protected $primaryKey = 'idincident'; 
    public $timestamps = false;
    use HasFactory;

   protected $fillable = [
        'idreservation',
        'idutilisateur', 
        'idlocateur',    
        'typeincident',
        'datedeclaration',
        'datecloture',
        'description',
        'reconnuparproprietaire',
        'remboursementvalide',
        'statut_reglament' 
    ];

    public function annonceincident()
{
    
    return $this->hasMany(Annonce::class, 'idannonce', 'idannonce');
}
public function compteUtilisateur()
    {
        
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }

    
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation', 'idreservation');
    }

    
    public function declarant()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }
}
