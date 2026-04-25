<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends BaseMail
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Test Email - ' . config('app.name'))
                    ->view('emails.test')
                    ->with([
                        'time' => now()->format('Y-m-d H:i:s'),
                        'appName' => config('app.name')
                    ]);
    }
}
