<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Annonce;


class TypeHebergement extends Model
{
    
    protected $table = 'typehebergement'; 
    
    protected $primaryKey = 'idtypehebergement';
    
    public $timestamps = false; 

    protected $fillable = ['typehebergement'];

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'idtypehebergement', 'idtypehebergement');
    }
}