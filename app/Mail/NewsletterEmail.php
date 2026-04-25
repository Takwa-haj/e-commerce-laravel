<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterEmail extends BaseMail
{
    use Queueable, SerializesModels;

    public $name;
    public $subject;
    public $content;
    public $unsubscribeLink;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $subject, $content)
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->content = $content;
        $this->unsubscribeLink = '#';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.newsletter')
                    ->with([
                        'name' => $this->name,
                        'content' => $this->content,
                        'unsubscribeLink' => $this->unsubscribeLink
                    ]);
    }
}
