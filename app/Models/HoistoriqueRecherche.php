<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoistoriqueRecherche extends Model
{
    use HasFactory;

    protected $table = 'hoistoriquerecherche';
    protected $primaryKey = 'idrecherche';

    protected $fillable = [
        'idutilisateur',
        'iddate',
        'idville',
        't_108',
        'prix_min', 
        'prix_max',
        'nbchambre_min',
        'animaux_acceptes', 
        'fumeurs_autorises',
        'idtypehebergement',
    ];

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'idville', 'idville');
    }

    public function typeHebergement()
    {
        return $this->belongsTo(TypeHebergement::class, 'idtypehebergement', 'idtypehebergement');
    }

}
