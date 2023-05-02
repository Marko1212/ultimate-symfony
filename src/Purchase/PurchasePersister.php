<?php


namespace App\Purchase;

use App\Cart\CartService;
use DateTime;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersister
{
    public function __construct(private readonly Security $security, private readonly CartService $cartService, private readonly EntityManagerInterface $em)
    {
    }

    public function storePurchase(Purchase $purchase)
    {
        // 6. Nous allons la lier avec l'utilisateur actuellement connecte (Security)
        $purchase->setUser($this->security->getUser());

        $this->em->persist($purchase);

        // 7. Nous allons la lier avec les produits qui sont dans le panier (CartService)
        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setQuantity($cartItem->qty)
                ->setProductPrice($cartItem->product->getPrice())
                ->setTotal($cartItem->getTotal());

            $this->em->persist($purchaseItem);
        }

        // 8. Nous alonrs enregistrer la commande (EntityManagerInterface)

        $this->em->flush();
    }
}
