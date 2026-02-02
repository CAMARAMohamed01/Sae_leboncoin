<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prorprietaire extends Model
{
    protected $table = 'Particulier';
    protected $primaryKey = 'idParticulier';
    public $timestamps = false;
    use HasFactory;
}

public function professionnel()  {
    return $this->hasMany(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
}

public function annonces()
{
    return $this->hasMany(Annonce::class, 'idProprietaire', 'idProprietaire'); 
}