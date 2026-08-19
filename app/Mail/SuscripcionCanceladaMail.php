<?php

namespace App\Mail;

use App\Models\Suscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuscripcionCanceladaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Suscripcion $suscripcion) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu renovación AMM ha sido cancelada',
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.suscripcion-cancelada');
    }

    public function attachments(): array
    {
        return [];
    }
}
