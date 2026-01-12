<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // IMPORTANT : Force le nom de la table au singulier
    protected $table = 'service';

    protected $primaryKey = 'idservice';
    public $timestamps = false;

    protected $fillable = ['nomservice'];
}