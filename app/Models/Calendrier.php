<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendrier extends Model
{
    use HasFactory;

    protected $table = 'calendrier';     
    protected $primaryKey = 'idcalendrier';
    public $timestamps = false;

    
    public function prix()
    {
        return $this->hasMany(Definit::class, 'idcalendrier');
    }
}
