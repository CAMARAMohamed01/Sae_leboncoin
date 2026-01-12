<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class Professionnel extends Authenticatable
{
    protected $table = 'professionnel'; 
    protected $primaryKey = 'idprofessionnel'; 
    public $timestamps = false;

    protected $fillable = [
        'idutilisateur', 
        'siret',
        'nomprofessionnel',
        'secteuractivite'
    ];
    public function adresse()
    {
        
        return $this->belongsTo(Adresse::class, 'idadresse', 'idadresse');
    }
    public function annoncedumemeproffessionnel()
{
    
    return $this->hasMany(Annonce::class, 'idutilisateur', 'idutilisateur');
}
public function avisRelation()
{
    
    return $this->hasMany(Avis::class, 'idutilisateur', 'idutilisateur');
}
public function avis()
{
    
    return $this->hasMany(Avis::class, 'idutilisateur', 'idutilisateur');
}
public function getNoteMoyenneAttribute()
{
    
    $avis = $this->avisRelation->note; 

    if ($avis->isEmpty()) {
        return 5; 
    }
    
    
    return $avis->avg('note');
}
// public function avisPro()
// {
//     $lesavis = $this->hasMany(Avis::class, 'idutilisateur', 'idutilisateur');
//     $diviseur = 0;
//     $note = 0;
//     if($diviseur == 0){
//         $note = 5;
//     }
//     else{
//     foreach($lesavis as $noteavis){
//         $note= $note + $noteavis->note;
//         $diviseur = $diviseur + 1;
//     }
//     $note = $note / $diviseur;}

//     return $note;
// }
}