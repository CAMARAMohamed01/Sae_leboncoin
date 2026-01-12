<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrixPeriode extends Model
{
    
    protected $table = 'prixperiode';

    
    protected $primaryKey = 'idperiode'; 

    
    public $timestamps = false;

    
    protected $fillable = [
        'idannonce',
        'nomperiode',
        'prix'
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'idannonce', 'idannonce');
    }
}