<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\VerificationRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Helpers\Helper;


final class VerificationRequestMailSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail( $event)
    {
        $helper = new Helper();
        $verificationRequest = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$verificationRequest instanceof VerificationRequest || Request::METHOD_GET !== $method || !isset($_SESSION['DECISION'])) {
            return;
        }

        if ($_SESSION['DECISION'] == 1) $status = 'Approved';
        if ($_SESSION['DECISION'] == 2) $status = 'Denied';
        $_SESSION['DECISION'] = '';

        $email = (new Email())
            ->from($helper::FROM_EMAIL)
            ->to($helper::TO_EMAIL)
            ->subject('Your Request has been ' . $status)
            ->html('<p>Your request has been ' . $status . '</p>');

        $this->mailer->send($email);
    }
}