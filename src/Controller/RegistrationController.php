<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    // Route pour afficher et traiter le formulaire d'inscription
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response
    {
        // Création d’un nouvel objet User (vide)
        $user = new User();

        //Création du formulaire basé sur RegistrationFormType
        // On lie directement le formulaire à l’objet User
        $form = $this->createForm(RegistrationFormType::class, $user);

        //Traitement de la requête HTTP (remplit le formulaire avec les données envoyées)
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis ET qu’il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var string $plainPassword */
            // Récupération du mot de passe en clair depuis le formulaire
            $plainPassword = $form->get('plainPassword')->getData();

            // Hash du mot de passe (sécurité Symfony)
            // On ne stocke jamais le mot de passe en clair
            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $plainPassword)
            );

            // Préparation de l’insertion en base de données
            $entityManager->persist($user);

            // Exécution de la requête (INSERT en base)
            $entityManager->flush();

            // Redirection vers la page d’accueil après inscription
            return $this->redirectToRoute('app_home');
        }

        // Affichage du formulaire dans la vue Twig
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}