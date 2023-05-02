<?php

namespace App\EventDispatcher;

use App\Event\ProductViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ProductViewEmailSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger, private readonly MailerInterface $mailer)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.view' => 'sendEmail'
        ];
    }

    public function sendEmail(ProductViewEvent $productViewEvent)
    {
        /*  $email = new TemplatedEmail();
        $email->from(new Address('contact@mail.com', 'Infos de la boutique'))
            ->to('admin@mail.com')->text('Un visiteur est en train de voir la page du produit n° ' . $productViewEvent->getProduct()->getId())
            ->htmlTemplate("emails/product_view.html.twig")
            ->context([
                'product' => $productViewEvent->getProduct()
            ])
            ->subject('Visite du produit n° ' . $productViewEvent->getProduct()->getId());

        $this->mailer->send($email); */

        $this->logger->info("Email envoye a l'admin pour le produit n° " . $productViewEvent->getProduct()->getId());
    }
}
