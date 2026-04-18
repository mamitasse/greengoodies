<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AccountController extends AbstractController
{
    //  Route pour accéder à la page "Mon compte"
    #[Route('/account', name: 'app_account')]
    
    // Sécurité : seul un utilisateur connecté avec ROLE_USER peut accéder
    #[IsGranted('ROLE_USER')]
    public function index(OrderRepository $orderRepository): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        //  Récupération des commandes liées à cet utilisateur
        // Triées par date décroissante (les plus récentes en premier)
        $orders = $orderRepository->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );

        //  Envoi des données à la vue Twig
        return $this->render('account/index.html.twig', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    // Route pour activer / désactiver l'accès API
    #[Route('/account/toggle-api', name: 'app_account_toggle_api', methods: ['GET'])]
    
    //  Sécurité : utilisateur connecté obligatoire
    #[IsGranted('ROLE_USER')]
    public function toggleApiAccess(EntityManagerInterface $entityManager): Response
    {
        // 👤 On précise le type pour éviter les erreurs
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        //  Inversion de la valeur (true → false / false → true)
        //  Permet d’activer ou désactiver l’accès API
        $user->setIsApiEnabled(!$user->isApiEnabled());

        // Préparation de la mise à jour en base
        $entityManager->persist($user);

        // Exécution de la mise à jour
        $entityManager->flush();

        // Redirection vers la page compte
        return $this->redirectToRoute('app_account');
    }

    // Route pour supprimer le compte utilisateur
    #[Route('/account/delete', name: 'app_account_delete')]
    
    //  Sécurité : utilisateur connecté obligatoire
    #[IsGranted('ROLE_USER')]
    public function deleteAccount(
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage
    ): Response {
        // Récupération de l'utilisateur connecté
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        //  Déconnexion de l'utilisateur (suppression du token de sécurité)
        $tokenStorage->setToken(null);

        //  Suppression de la session (panier, données utilisateur, etc.)
        $session->invalidate();

        // Suppression de l'utilisateur en base de données
        $entityManager->remove($user);

        //  Validation de la suppression
        $entityManager->flush();

        // Redirection vers la page d'accueil
        return $this->redirectToRoute('app_home');
    }
}