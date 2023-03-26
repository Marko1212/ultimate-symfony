<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController
{
    /**
     * @Route("/hello/{prenom?World}", name="hello", methods={"GET"}, host="localhost", 
     * schemes={"http", "https"})
     */
    public function hello(
        $prenom,
        LoggerInterface $logger,
        Calculator $calculator,
        Slugify $slugify,
        Environment $twig
    ) {
        dump($twig);
        dump($slugify->slugify("Hello World"));
        $logger->error("Mon message de log!");

        $tva = $calculator->calcul(100);

        dd($tva);

        return new Response("Hello $prenom!");
    }
}
