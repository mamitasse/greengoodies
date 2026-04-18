<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    // ➕ Ajouter un produit au panier
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(int $id, SessionInterface $session): Response
    {
        // Récupération du panier en session (tableau)
        // Si le panier n'existe pas encore, on initialise un tableau vide
        $cart = $session->get('cart', []);

        // Si le produit existe déjà dans le panier
        if (!empty($cart[$id])) {
            // ➕ on augmente la quantité
            $cart[$id]++;
        } else {
            // sinon on l'ajoute avec quantité 1
            $cart[$id] = 1;
        }

        // Mise à jour du panier en session
        $session->set('cart', $cart);

        // Redirection vers la page panier
        return $this->redirectToRoute('app_cart');
    }

    // Afficher le panier
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        // Récupération du panier depuis la session
        $cart = $session->get('cart', []);

        // Tableau qui contiendra les données complètes du panier
        $cartData = [];

        // Total du panier
        $total = 0;

        // Parcours du panier (id produit + quantité)
        foreach ($cart as $id => $quantity) {
            // Récupération du produit depuis la base de données
            $product = $productRepository->find($id);

            // Vérification que le produit existe
            if ($product) {
                //  On stocke le produit + sa quantité
                $cartData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];

                //  Calcul du total (prix × quantité)
                $total += $product->getPrice() * $quantity;
            }
        }

        // Envoi des données à Twig
        return $this->render('cart/index.html.twig', [
            'cart' => $cartData,
            'total' => $total,
        ]);
    }

    //Diminuer la quantité d’un produit
    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(int $id, SessionInterface $session): Response
    {
        // Récupération du panier
        $cart = $session->get('cart', []);

        // Vérifie si le produit existe dans le panier
        if (!empty($cart[$id])) {

            // ➖ Si quantité > 1 → on diminue
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                // 🗑️ Sinon on supprime complètement le produit
                unset($cart[$id]);
            }
        }

        // Mise à jour du panier
        $session->set('cart', $cart);

        //Redirection
        return $this->redirectToRoute('app_cart');
    }

    // Supprimer complètement un produit du panier
    #[Route('/cart/delete/{id}', name: 'app_cart_delete')]
    public function delete(int $id, SessionInterface $session): Response
    {
        //Récupération du panier
        $cart = $session->get('cart', []);

        //Si le produit existe
        if (isset($cart[$id])) {
            // Suppression du produit
            unset($cart[$id]);
        }

        // Mise à jour
        $session->set('cart', $cart);

        // Redirection
        return $this->redirectToRoute('app_cart');
    }

    //  Vider complètement le panier
    #[Route('/cart/clear', name: 'app_cart_clear')]
    public function clear(SessionInterface $session): Response
    {
        //  Suppression de la variable "cart" en session
        $session->remove('cart');

        //  Redirection vers panier vide
        return $this->redirectToRoute('app_cart');
    }
}