<?php

// src/Controller/CartController.php

namespace App\Controller;


use App\Form\CartType;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/form", name="cart_form")
     */
    public function cartForm(Request $request): Response
    {
        // Récupérer l'adresse de l'utilisateur
        $user = $this->getUser();
        // Fix undefined method error
        //$adresse = $user->getAdresse();

        $form = $this->createForm(CartType::class, null, [
            //'adresse' => $adresse,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitez le formulaire, par exemple, enregistrez le panier dans la base de données
            // ...

            // Redirigez où vous le souhaitez après la soumission réussie
            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

/**
 * @Route("/cart", name="cart_index")
 */
public function index(): Response
{
    // Récupérer les produits du panier depuis la session
    $cart = $this->get('session')->get('cart', []);

    // Charger les produits depuis la base de données
    $produits = $this->getDoctrine()->getRepository(Produit::class)->findBy(['id' => $cart]);

    // Initialiser le total du panier en dehors de la boucle
    $total = 0;

    // Calculer le total du panier
    foreach ($produits as $produit) {
        $total += $produit->getPrix(); // Assurez-vous que votre entité Produit a une méthode getPrix()
    }
   
    return $this->render('cart/index.html.twig', [
        'produits' => $produits,
        'total' => $total,
    ]);
    }
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function addToCart($id): Response
    {
        // Récupérer le panier depuis la session
        $cart = $this->get('session')->get('cart', []);
       
        // Ajouter le produit à la session
        $cart[] = $id;
        $this->get('session')->set('cart', $cart);

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function removeFromCart($id): Response
    {
        // Récupérer le panier depuis la session
        $cart = $this->get('session')->get('cart', []);

        // Retirer le produit du panier
        $key = array_search($id, $cart);
        if ($key !== false) {
            unset($cart[$key]);
            $this->get('session')->set('cart', array_values($cart));
        }

        return $this->redirectToRoute('cart_index');
    }
    
}

