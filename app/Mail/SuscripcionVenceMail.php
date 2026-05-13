<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuscripcionVenceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public \App\Models\Suscripcion $suscripcion, public int $diasRestantes) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Tu suscripción AMM vence en {$this->diasRestantes} días",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.suscripcion-vence',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
