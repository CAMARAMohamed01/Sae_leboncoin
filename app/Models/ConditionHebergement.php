<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConditionHebergement extends Model
{
    use HasFactory;

    
    protected $table = 'conditionhebergement';

    protected $primaryKey = 'idconditionheb'; 

    protected $fillable = [
        'heuredepart',
        'heurearrivee',
        'animauxacceptes', 
        'fumeur',          
    ];

    protected $casts = [
        'animauxacceptes' => 'boolean',
        'fumeur' => 'boolean',
        'heuredepart' => 'datetime', 
        'heurearrivee' => 'datetime',
    ];
}