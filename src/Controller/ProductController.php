<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'product_category', priority: -1)]
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            throw $this->createNotFoundException("La categorie demandee n'existe pas");
        }

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     */
    public function show($slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy([
            'slug' => $slug
        ]);
        if (!$product) {
            throw $this->createNotFoundException("Le produit demande n'existe pas");
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em
    ) {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     */
    public function edit(
        $id,
        ProductRepository $productRepository,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        ValidatorInterface $validator
    ) {
        /*  $client = [
            'nom' => '',
            'prenom' => 'Ma',
            'voiture' => [
                'marque' => '',
                'couleur' => 'Noire'
            ]
        ];

        $collection = new Collection([
            'nom' => new NotBlank(['message' => 'Le nom ne doit pas etre vide']),
            'prenom' => [
                new NotBlank(['message' => 'Le prenom ne doit pas etre vide']),
                new Length(['min' => 3, 'minMessage' => 'Le prenom ne doit pas faire moins de 3 caracteres'])
            ],
            'voiture' => new Collection([
                'marque' => new NotBlank(['message' => 'La marque de la voiture est obligatoire']),
                'couleur' => new NotBlank(['message' => 'La couleur de la voiture est obligatoire'])
            ])
        ]);

        $resultat = $validator->validate($client, $collection);
 */
        /* $product = new Product; */
        //   $product->setName('toto')->setPrice(1000);

        /*   $resultat = $validator->validate($product);

        if ($resultat->count() > 0) {
            dd("Il y a des erreurs", $resultat);
        }

        dd("Tout va bien"); */
        /* 
        $product = new Product;
        $resultat = $validator->validate($product, null, ["Default", "with-price"]);
        dd($resultat);
 */
        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product, /*["validation_groups" => ["large-name", "with-price"]] */);

        //  $form->setData($product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $em->flush();
            /*             $url = $urlGenerator->generate('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]); */

            /*             $response = new RedirectResponse($url);
            return $response; */

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView
        ]);
    }
}
