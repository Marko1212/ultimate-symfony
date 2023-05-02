<?php

namespace App\EventDispatcher;

use App\Entity\User;
use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Security;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger, private readonly MailerInterface $mailer, private readonly Security $security)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {
        // 1. Recuperer l'utilisateur actuellement en ligne (pour connaitre son adresse)
        // Security
        /** @var User */
        $currentUser = $this->security->getUser();

        // 2. Recuperer la commande (je la trouverai dans PurchaseSuccessEvent)
        $purchase = $purchaseSuccessEvent->getPurchase();

        // 3. Ecrire le mail (nouveau TemplatedEmail)
        $email = new TemplatedEmail();
        $email->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
            ->from("contact@mail.com")
            ->subject("Bravo, votre commande ({$purchase->getId()}) a bien ete confirmee")
            ->htmlTemplate('emails/purchase_success.html.twig')
            ->context([
                'purchase' => $purchase,
                'user' => $currentUser
            ]);

        // 4. Envoyer l'email
        // MailerInterface
        $this->mailer->send($email);

        $this->logger->info("Email envoye pour la commande n° " . $purchaseSuccessEvent->getPurchase()->getId());
    }
}
