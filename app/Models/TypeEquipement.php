<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEquipement extends Model
{
    // forcer laravel à utiliser le nom typeequipement au lieu de type_equipements (son automatisme)
    protected $table = 'typeequipement'; 

    // On précise aussi la clé primaire car ce n'est pas "id"
    protected $primaryKey = 'idtypeequipement';
    
    // Pas de colonnes created_at / updated_at dans ta BDD
    public $timestamps = false;
}