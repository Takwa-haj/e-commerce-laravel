<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $subjectLine;
    public $messageContent;
    public $template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $subject, $message, $template = 'default')
    {
        $this->name = $name;
        $this->subjectLine = $subject;
        $this->messageContent = $message;
        $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.custom')
                    ->with([
                        'name' => $this->name,
                        'content' => $this->messageContent,
                        'template' => $this->template,
                        'subjectLine' => $this->subjectLine
                    ]);
    }
}
