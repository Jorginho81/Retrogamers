<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatalogueController extends AbstractController

{    
    #[Route('/catalogue', name: 'app_catalogue', methods: ['GET', 'POST'])]
    public function catalogue(): Response
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();

        return $this->render('catalogue/index.html.twig', [
            'produits' => $produits,
        ]);
    }
}
