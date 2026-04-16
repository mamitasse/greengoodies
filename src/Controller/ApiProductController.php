<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        $user = $this->getUser();

        // ❌ Pas connecté
        if (!$user) {
            return $this->json([
                'message' => 'Non authentifié',
            ], 401);
        }

        // ❌ Accès API désactivé
        if (!$user->isApiEnabled()) {
            return $this->json([
                'message' => 'Accès API non activé',
            ], 403);
        }

        // ✅ Récupération produits
        $products = $productRepository->findAll();

        // ✅ Serializer Symfony (IMPORTANT)
        return $this->json($products, 200, [], [
            'groups' => ['product:read']
        ]);
    }
}