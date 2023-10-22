<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(array $params = []): void
    {
        $email = (new Email())
            ->from($params['from'])
            ->to($params['to'])
            ->subject($params['subject'])
            ->text($params['body']);

        $this->mailer->send($email);
    }
}
