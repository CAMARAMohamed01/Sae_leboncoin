<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    use HasFactory;

    
    protected $table = 'ville';
    protected $primaryKey = 'idville';
    public $timestamps = false;
    protected $fillable = ['nomville', 'cpville'];
    
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'idville');
    }
}