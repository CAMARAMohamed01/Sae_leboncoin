<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CompteUtilisateur;
use App\Models\Annonce;
use App\Models\Reservation;
class Message extends Model
{
    protected $table = 'message';
    protected $primaryKey = 'idmessage';
    public $timestamps = false;

    protected $fillable = [
        'idannonce',
        'idutilisateur',      
        'com_idutilisateur',  
        'idreservation',
        'contenu',
        'dateenvoi'
    ];

    public function expediteur()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'idutilisateur', 'idutilisateur');
    }

    public function destinataire()
    {
        return $this->belongsTo(CompteUtilisateur::class, 'com_idutilisateur', 'idutilisateur');
    }
    public function reservation() { return $this->belongsTo(Reservation::class, 'idreservation', 'idreservation'); }
    public function annonce() { return $this->belongsTo(Annonce::class, 'idannonce', 'idannonce'); }
}