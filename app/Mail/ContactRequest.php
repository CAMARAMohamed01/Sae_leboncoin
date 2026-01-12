<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class ContactRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $filePath;

    // On récupère les données et le chemin du fichier (s'il y en a un)
    public function __construct($data, $filePath = null)
    {
        $this->data = $data;
        $this->filePath = $filePath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande : ' . $this->data['subject'],
        );
    }

    public function content(): Content
    {
        // On va créer cette vue juste après
        return new Content(
            view: 'emails.contact',
        );
    }

    public function attachments(): array
    {
        if ($this->filePath) {
            return [
                Attachment::fromPath($this->filePath),
            ];
        }
        return [];
    }
}