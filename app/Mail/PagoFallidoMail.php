<?php

namespace App\Mail;

use App\Models\Suscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PagoFallidoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Suscripcion $suscripcion,
        public float $monto,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'No pudimos cobrar tu renovación AMM — Acción requerida',
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.pago-fallido');
    }

    public function attachments(): array
    {
        return [];
    }
}
