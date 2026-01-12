<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;

class ReservationAcceptee extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('no-reply@leoncoin.fr', 'Leboncoin Réservations'),
            subject: ' Mauvaise Nouvelle ! Votre réservation est refusée. ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation_refusee',
        );
    }
}