<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identite extends Model
{
    protected $table = 'identite';
    protected $primaryKey = 'ididentite';
    public $timestamps = false;

    protected $fillable = [
        'idutilisateur',
        'numeroidentite',
        'typeidentite', 
        'dateexpirationidentite',
        'lien_document' 
    ];

    public function compte()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }
}
