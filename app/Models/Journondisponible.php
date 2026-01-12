<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journondisponible extends Model
{
    
    protected $table = 'journondisponible';

    
    protected $primaryKey = 'idjour'; 

    
    public $timestamps = false;

    

    public function annonce()
    {
        return $this->hasMany(Annonce::class, 'idannonce', 'idannonce');
    }
}