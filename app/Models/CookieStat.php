<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CookieStat extends Model
{
    protected $table = 'cookiestats'; 
    protected $primaryKey = 'idstat';
    public $timestamps = false;

    protected $fillable = ['choix', 'date_action'];
}