<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendrier extends Model
{
    use HasFactory;

    protected $table = 'calendrier';     // Nom de ta table
    protected $primaryKey = 'idcalendrier';
    public $timestamps = false;

    // Un calendrier peut avoir plusieurs prix (Definit)
    public function prix()
    {
        return $this->hasMany(Definit::class, 'idcalendrier');
    }
}
