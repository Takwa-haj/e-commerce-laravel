<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Mailtrap\EmailHeader\CategoryHeader;
use Mailtrap\EmailHeader\CustomVariableHeader;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\UnstructuredHeader;

abstract class BaseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Add Mailtrap specific headers
     */
    protected function addMailtrapHeaders(Email $email, array $customVariables = []): void
    {
        $email->getHeaders()
            ->addTextHeader('X-Message-Source', env('APP_URL', 'localhost'))
            ->add(new UnstructuredHeader('X-Mailer', 'Laravel Mailtrap Integration'))
        ;

        // Add custom variables
        foreach ($customVariables as $key => $value) {
            $email->getHeaders()->add(new CustomVariableHeader($key, $value));
        }

        // Add category
        $email->getHeaders()->add(new CategoryHeader('E-commerce Notifications'));
    }
}
