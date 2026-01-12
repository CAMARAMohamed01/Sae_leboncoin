<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    
    protected $table = 'photo';
    
    
    protected $primaryKey = 'idphoto';
    
    
    public $timestamps = false;

    
    protected $fillable = [
        'lienurl',
        'legende',
        'idannonce'
    ];

    
    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'idannonce', 'idannonce');
    }
}