<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    
    protected $table = 'adresse';
    protected $primaryKey = 'idadresse';

   
    public $timestamps = false;
    public $incrementing = true; 
    use HasFactory;
    protected $fillable = [
        'idville', 
        'nomrue', 
        'voie', 
        'latitude', 
        'longitude'
    ];

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'idville', 'idville');
    }
}
