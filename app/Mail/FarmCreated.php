<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class FarmCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($farmData)
    {
        $this->farmData = $farmData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('components.mail', ['farmData' => $this->farmData]);
    }

    public function envelope(): Envelope
{
    return new Envelope(
        from: new Address('mujtabashafqat0@gmail.com', 'Mujtaba Shafqat'),
        subject: 'Farm Created!',
    );
}

}
