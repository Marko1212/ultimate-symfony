<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Twig\Environment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PurchasesListController extends AbstractController
{
    /*     public function __construct(
        private readonly Security $security,
        private readonly RouterInterface $router,
        private readonly Environment $twig
    ) {
    } */

    /**
     * @Route("/purchases", name="purchase_index")
     * @IsGranted("ROLE_USER", message="Vous devez etre connecte pour acceder a vos commandes")
     */
    public function index()
    {
        // 1. Nous devons nous assurer que la personne est connectee (sinon redirection vers la page d'accueil) -> Security
        /** @var User */
        $user = $this->getUser();

        // if (!$user) {
        // Redirection -> RedirectResponse
        // Generer une URL en fonction du nom d'une route -> UrlGeneratorInterface ou RouterInterface
        /*             $url = $this->router->generate('homepage');
            return new RedirectResponse($url); */
        //     throw new AccessDeniedException("Vous devez etre connecte pour accepter a vos commandes");
        // }

        // 2. Nous voulons savoir QUI est connecte -> Security
        // 3. Nous voulons passer l'utilisateur connecte a Twig afin d'afficher ses commandes -> Environment de Twig / Response
        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
        /*         $html = $this->twig->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
        return new Response($html); */
    }
}
