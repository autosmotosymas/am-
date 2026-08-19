<?php

namespace App\Mail;

use App\Models\Suscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenovacionCobradaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Suscripcion $suscripcion,
        public float $monto,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu plan AMM se renovó exitosamente',
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.renovacion-cobrada');
    }

    public function attachments(): array
    {
        return [];
    }
}
