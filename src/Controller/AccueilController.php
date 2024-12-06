<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(ProduitRepository $produitRepository): Response
    {
        // Récupère tous les produits
        $produits = $produitRepository->findAllProduits();

        // Passe les produits à la vue
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'produits' => $produits, // Données à envoyer à Twig
        ]);
    }

    #[Route('/produit/{id}', name:'app_produit')]
    public function show(ProduitRepository $produitRepository, int $id): Response
    {
        $produit = $produitRepository->findProduitById($id);
        return $this->render('accueil/produit.html.twig', [
            "produit" => $produit,
        ]);
    }
}
