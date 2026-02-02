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
        'idutilisateur',   
        'idreservation',   
        'idannonce',       
        'note',            
        'commentaire', 
        'avis_expert'    
    ];
    
    protected $attributes = [
        'avis_expert' => 'En attente',
    ];

    public function annonce()
{
    return $this->belongsTo(Annonce::class, 'idAnnonce', 'idAnnonce'); 
    
} 


    public function auteur()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation', 'idreservation');
    }
}

