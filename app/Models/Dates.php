<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dates extends Model
{
    protected $table = 'dates';
    protected $primaryKey = 'iddate';
    public $timestamps = false;
}
