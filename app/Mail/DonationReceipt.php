<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class DonationReceipt extends Mailable
{
    public function __construct(public Donation $donation)
    {
    }

    public function envelope(): Envelope
    {
        $reply = config('sahabat.email');

        return new Envelope(
            subject: 'Terima kasih, donasimu sudah kami terima',
            replyTo: filter_var($reply, FILTER_VALIDATE_EMAIL) ? [new Address($reply, config('sahabat.org_name'))] : [],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.donation-receipt', with: [
            'd' => $this->donation,
            'c' => $this->donation->campaign->fresh(),
            'o' => config('sahabat'),
        ]);
    }
}
