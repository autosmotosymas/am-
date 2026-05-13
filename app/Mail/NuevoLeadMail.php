<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevoLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        $vehiculo = $this->lead->vehiculo;
        return new Envelope(
            subject: "Nuevo contacto — {$vehiculo->anio} {$vehiculo->marca} {$vehiculo->modelo}",
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.nuevo-lead');
    }

    public function attachments(): array
    {
        return [];
    }
}
