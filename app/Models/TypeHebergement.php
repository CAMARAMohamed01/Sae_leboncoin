<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeHebergement extends Model
{
    // Nom exact de la table
    protected $table = 'typehebergement'; 
    // Ta clé primaire personnalisée
    protected $primaryKey = 'idtypehebergement';
    // Indique si la table a des colonnes created_at/updated_at (mets false si absentes)
    public $timestamps = false; 

    protected $fillable = ['typehebergement'];
}