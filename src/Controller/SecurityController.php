<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Route pour afficher la page de connexion
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère la dernière erreur d'authentification (ex : mauvais mot de passe)
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier identifiant saisi par l'utilisateur (email)
        // Cela permet de pré-remplir le champ après un échec
        $lastUsername = $authenticationUtils->getLastUsername();

        // Affiche la page de connexion avec les données récupérées
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    // Route de déconnexion
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode est volontairement vide
        // Symfony intercepte automatiquement la déconnexion via le firewall (security.yaml)
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}