<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $table = 'annonce';
    protected $primaryKey = "idannonce";
    public $timestamps = false;

    /**
     * Adresse de l'annonce
     */
    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }
    public function definit()
    {
        return $this->belongsTo(Definit::class, 'idannonce','idcalendrier');
    }

    /**
     * Ville associée à l'annonce
     */
    public function ville()
    {
        return $this->belongsTo(Ville::class, 'idville');
    }

    /**
     * Photos de l'annonce
     */
    public function photos()
    {
        return $this->hasMany(Photo::class, 'idannonce');
    }

    /**
     * Prix de l'annonce
     * (1 seul prix → hasOne ; plusieurs prix → hasMany)
     */
    public function prix()
    {
        return $this->hasMany(Definit::class, 'idannonce');
    }

    /**
     * Type d'hébergement
     */
    public function typeHebergement()
    {
        return $this->belongsTo(TypeHebergement::class, 'idtypehebergement');
    }

    // Récupérer les équipements (Table de liaison 'peut_contenir')
    public function equipements()
    {
        // belongsToMany(ModèleCible, TablePivot, CléEtrangèreAnnonce, CléEtrangèreCible)
        return $this->belongsToMany(TypeEquipement::class, 'peut_contenir', 'idannonce', 'idtypeequipement');
    }

    public function dateEnregistrement()
{
    // On lie le champ 'iddate' de l'annonce a la classe/model Dates
    return $this->belongsTo(Dates::class, 'iddate', 'iddate');
}

    // Récupérer les services inclus (Table de liaison 'inclue')
    public function services()
    {
        return $this->belongsToMany(TypeService::class, 'inclue', 'idannonce', 'idtypeservice');
    }
    // tarifs

    public function tarifs(){
        return $this->hasMany(Definit::class, 'idannonce');
    }

    /**
     * Champs modifiables
     */
    protected $fillable = [
        'idtypehebergement',
        'idconditionheb',
        'iddate',
        'idville',
        'idadresse',
        'titreannonce',
        'descriptionannonce'
    ];
}
