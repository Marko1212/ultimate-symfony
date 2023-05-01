<?php

namespace App\Controller\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class PurchaseConfirmationController extends AbstractController
{
    public function __construct(private readonly CartService $cartService, private readonly EntityManagerInterface $em, private readonly PurchasePersister $persister)
    {
    }
    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez etre connecte pour confirmer une commande")
     */
    public function confirm(Request $request)
    {
        // 1. Nous voulons lire les donnees du formulaire
        // FormFactoryInterface / Request
        $form = $this->createForm(CartConfirmationType::class);
        //  $form = $this->formFactory->create(CartConfirmationType::class);

        $form->handleRequest($request);

        // 2. Si le formulaire n'a pas ete soumis : degager
        if (!$form->isSubmitted()) {
            // Message Flash puis redirection
            /*   $flashBag->add('warning', 'Vous devez remplir le formulaire de confirmation'); */
            $this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');

            return $this->redirectToRoute('cart_show');
            //   return new RedirectResponse($this->router->generate('cart_show'));
        }

        // 3. Si je ne suis pas connecte : degager (Security)
        //     $user = $this->security->getUser();
        // $user = $this->getUser();

        /*         if (!$user) {
            throw new AccessDeniedException("Vous devez etre connecte pour confirmer une commande");
        } */

        // 4. S'il n'y a pas de produits dans mon panier : degager (CartService)
        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) === 0) {
            $this->addFlash('warning', 'Vous ne pouvez confirmer une commande avec un panier vide');
            return $this->redirectToRoute('cart_show');
        }

        // 5. Nous allons creer une Purchase
        /** @var Purchase */
        $purchase = $form->getData();

        $this->persister->storePurchase($purchase);

        /*         $this->cartService->empty();

        $this->addFlash('success', 'La commande a bien ete enregistree'); */

        return $this->redirectToRoute('purchase_payment_form', [
            'id' => $purchase->getId()
        ]);
    }
}
