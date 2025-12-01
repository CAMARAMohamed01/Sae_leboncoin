<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Particulier extends Model
{
    protected $fillable = [
        'siret',
        'nom',
        'adresse',
        'ville',
        'code_postal',
    ];
}