<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationMessage extends Model
{
    use HasFactory;

    protected $fillable = ['reservation_id', 'user_id', 'message', 'is_read'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'idreservation');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}