<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    public string $url;

    public function __construct(
        public ContactSubmission $submission,
        public string $subjectPrefix = '[PixelCraftsLab]',
    ) {
        $this->url = route('admin.enquiries.show', $submission);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trim($this->subjectPrefix.' New enquiry from '.$this->submission->name),
            replyTo: [$this->submission->email],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.contact-submission');
    }
}
