<?php

namespace App\Mail;

use App\Models\absence as AbsenceRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbsenceRequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AbsenceRecord $absence) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('ui.mail.absence_approved_subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.absence-request-approved',
            with: ['absence' => $this->absence],
        );
    }
}
