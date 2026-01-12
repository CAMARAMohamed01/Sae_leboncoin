<?php

namespace App\Models;

// 

use App\Models\Particulier; // Assurez-vous que le modèle est importé
use App\Models\Professionnel;
//

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rechercher extends Model
{
    use HasFactory;
    protected $table = 'rechercher';
    protected $primaryKey = "idannonce";
    public $timestamps = false;

    public function rechercherpersonne(){
        return $this->hasMany(Annonce::class, 'idannonce', 'idannonce');
    
    }
    public function rechercherpersonnenom(){
        return $this->hasMany(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    
    }

    public function particulier()
    {
        
        return $this->belongsTo(\App\Models\Particulier::class, 'idparticulier', 'idparticulier');
    }
    
    
    public function professionnel()
    {
        
        return $this->belongsTo(\App\Models\Professionnel::class, 'idprofessionnel', 'idprofessionnel');
    }
    

    
}




