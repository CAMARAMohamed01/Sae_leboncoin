<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Definit extends Model
{
    use HasFactory;

    protected $table = 'definit';       // Nom de ta table
    protected $primaryKey = 'iddefinit'; // Clé primaire
    public $timestamps = false;          // Si ta table n’a pas created_at / updated_at

    // Relation vers l'annonce
    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'idannonce');
    }

    // Relation vers le calendrier si nécessaire
    public function calendrier()
    {
        return $this->belongsTo(Calendrier::class, 'idcalendrier');
    }
    
}
