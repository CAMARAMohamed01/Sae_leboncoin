<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Particulier extends Model
{
    use HasFactory;

    protected $table = 'particulier';
    protected $primaryKey = 'idparticulier';
    public $timestamps = false;

    protected $fillable = [
        'idutilisateur',
        'nomparticulier',
        'prenomparticulier',
        'civilite',
        'datenaissance',
    ];
    // protected $fillable = [
    //     'idutilisateur', 
    //     'nom',
    //     'prenom',
    //     'civilite',
    //     'date_naissance',
    //     'adresse',
    //     'ville',
    //     'code_postal',
    //     'telephone'
    // ];

    public function compte()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }
    public function rechercherparticuliernom(){
        return $this->hasOne(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }
    public function adresse()
{
    
    return $this->belongsTo(Adresse::class, 'idadresse', 'idadresse');
}
public function annoncedumemeprop()
{
    
    return $this->hasMany(Annonce::class, 'idutilisateur', 'idutilisateur');
}

    
}