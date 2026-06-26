<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactoAmmMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nombre,
        public string $telefono,
        public string $correo,
        public string $comentarios,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Contacto AMM — {$this->nombre}",
            replyTo: [$this->correo],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contacto-amm',
        );
    }
}
