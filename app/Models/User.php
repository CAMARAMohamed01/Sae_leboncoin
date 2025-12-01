<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // 1. Indiquez le nom exact de votre table dans la BDD
    protected $table = 'compteutilisateur'; 
    
    // 2. Indiquez la clé primaire (si ce n'est pas 'id')
    protected $primaryKey = 'idutilisateur'; 

    // Si votre clé primaire n'est pas un auto-increment (ex: un string), décommentez ceci :
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'idutilisateur',
        'motdepasse',
    ];

    protected $hidden = [
        'motdepasse',
    ];

    // 3. IMPORTANT : Dire à Laravel que le mot de passe est dans la colonne 'mdp'
    public function getAuthPassword()
    {
        return $this->motdepasse;
    }
}