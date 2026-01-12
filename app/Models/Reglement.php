<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reglement extends Model
{
    protected $table = 'reglement';
    protected $primaryKey = 'numeroreglement';
    public $timestamps = false;

    protected $fillable = [
        'idreservation',
        'idutilisateur',
        'modereglement', // 'Carte', 'Virement', etc.
        'montant',
        'statut_reglament' // 'Validé', 'En attente', etc.
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation', 'idreservation');
    }

    public function payeur()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }
}