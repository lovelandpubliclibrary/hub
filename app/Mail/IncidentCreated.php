<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Incident;

class IncidentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $incident;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Incident Created: ' . $this->incident->title)
                    ->markdown('emails.incidents.incident-created');
    }
}
