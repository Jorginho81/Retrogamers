<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Image;
use App\Repository\ImageRepository;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * BACK OFFICE
 * Préfixe de toutes les routes se trouvant dans ce controller
 * @Route("/admin/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * Cette route va afficher tous les produits de la BDD
     * Requête SELECT
     * 
     * @Route("/afficher", name="produit_afficher")
     */
    public function afficherProduits(ProduitRepository $produitRepository, ImageRepository $imageRepository): Response
    {
        $produits = $produitRepository->findAll();

        $image = $imageRepository->findAll();

        return $this->render('produit/produit_afficher.html.twig', [
            'produits' => $produits,
           
        ]);
    }

    /**
     * Ajouter par un formulaire un produit en bdd
     * requête INSERT INTO
     * 
     * @Route("/ajouter", name="produit_ajouter")
     */
    public function ajouterProduit(Request $request, EntityManagerInterface $em): Response
    {
        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_afficher');
        }

        return $this->render('produit/produit_ajouter.html.twig', [
            'formProduit' => $form->createView()
        ]);
    }

    /**
     * @Route("/fiche/{id}", name="produit_fiche")
     */
    public function ficheProduit(Produit $produit): Response
    {
        return $this->render('produit/produit_fiche.html.twig', [
            'produit' => $produit
        ]);
    }

    /**
     * Requête UPDATE
     * @Route("/modifier/{id}", name="produit_modifier")
     */
    public function modifierProduit(Produit $produit, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_afficher');
        }

        return $this->render('produit/produit_modifier.html.twig', [
            'formProduit' => $form->createView()
        ]);
    }

    /**  
     * Requête DELETE
     * @Route("/supprimer/{id}", name="produit_supprimer")
     */
    public function supprimerProduit(Produit $produit, EntityManagerInterface $em): Response
    {
        $em->remove($produit);
        $em->flush();

        return $this->redirectToRoute('produit_afficher');
    }
}
