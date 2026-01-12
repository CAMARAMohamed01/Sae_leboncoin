<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Annonce;

class Avis extends Model
{
    protected $table = 'avis';
    protected $primaryKey = 'idavis';
    public $timestamps = false;
    use HasFactory;


    protected $fillable = [
        'idutilisateur',   // L'auteur (Locataire ou Expert)
        'idreservation',   // La réservation liée (NULL si c'est un expert)
        'idannonce',       // L'annonce concernée
        'note',            
        'commentaire', 
        'avis_expert'    
    ];
    
    // Définition des valeurs par défaut pour les nouvelles instances
    protected $attributes = [
        'avis_expert' => 'En attente',
    ];

    public function annonce()
{
    return $this->belongsTo(Annonce::class, 'idAnnonce', 'idAnnonce'); 
    
} 



    // Relation vers l'auteur de l'avis
    public function auteur()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }

    // Relation vers la réservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation', 'idreservation');
    }
}

