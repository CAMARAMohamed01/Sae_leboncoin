<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeSuppression extends Model
{
    protected $table = 'demande_suppression';
    protected $primaryKey = 'id_demande';
    public $timestamps = false;

    protected $fillable = [
        'idutilisateur',
        'date_demande',
        'statut'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }
}