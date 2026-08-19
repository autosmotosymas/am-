<?php

namespace App\Mail;

use App\Models\Agencia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgenciaBienvenidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Agencia $agencia,
        public string $passwordPlano,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu agencia está lista en AutosMotosYMás!',
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.agencia-bienvenida');
    }

    public function attachments(): array
    {
        return [];
    }
}
