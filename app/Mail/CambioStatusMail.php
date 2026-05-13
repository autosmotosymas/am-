<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CambioStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public \App\Models\Vehiculo $vehiculo,
        public string $statusNuevo,
    ) {}

    public function envelope(): Envelope
    {
        $v = $this->vehiculo;
        $etiqueta = match($this->statusNuevo) {
            'apartado' => 'Ya está apartado',
            'vendido'  => 'Ya fue vendido',
            default    => 'Actualización de status',
        };
        return new Envelope(
            subject: "{$etiqueta} — {$v->anio} {$v->marca} {$v->modelo}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.cambio-status',
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
