<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Photo extends Model
{
    protected $table = 'photo';
    protected $primaryKey = 'idphoto';
    public $timestamps = false;
}
