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
    #[Route('/account', name: 'app_account')]
    #[IsGranted('ROLE_USER')]
    public function index(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        $orders = $orderRepository->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    #[Route('/account/toggle-api', name: 'app_account_toggle_api', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function toggleApiAccess(EntityManagerInterface $entityManager): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $user->setIsApiEnabled(!$user->isApiEnabled());
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_account');
    }

    #[Route('/account/delete', name: 'app_account_delete')]
    #[IsGranted('ROLE_USER')]
    public function deleteAccount(
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $tokenStorage->setToken(null);
        $session->invalidate();

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}