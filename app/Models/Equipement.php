<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;

    
    protected $table = 'equipement'; 

    protected $primaryKey = 'idequipement';
    public $timestamps = false;

    protected $fillable = ['idtypeequipement', 'nomequipement'];

    
    public function typeEquipement()
    {
        
        return $this->belongsTo(TypeEquipement::class, 'idtypeequipement', 'idtypeequipement');
    }

    public function annonces()
    {
        return $this->belongsToMany(
            Annonce::class, 
            'contient', 
            'idequipement', 
            'idannonce'
        )->withPivot('idtypeequipement'); 
    }
}