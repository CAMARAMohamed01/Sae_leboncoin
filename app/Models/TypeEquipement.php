<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEquipement extends Model
{
    
    protected $table = 'typeequipement'; 

    
    protected $primaryKey = 'idtypeequipement';
    
    
    public $timestamps = false;
    protected $fillable = ['typeequipement'];
}
