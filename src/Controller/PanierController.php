<?php

namespace App\Controller;

use App\Entity\ContenuPanier;
use App\Entity\Panier;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $message = "";
        $panier = $user->getPanier();
        $contenuPanier = $panier->getContenuPanier();

        if (!$panier) {
            $message = "Vous n'avez pas de panier";
        } elseif (!$contenuPanier) {
            $message = "Votre panier est vide";
        }

        return $this->render('panier/index.html.twig', [
            "message" => $message,
            "contenuPanier" => $contenuPanier,
        ]);
    }

    #[Route("/panier/ajouter/{id}", name: "app_add_panier")]
    function add(EntityManagerInterface $entityManager, $id)
    {

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $produit = $entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException("Produit non trouvé.");
        }

        $panier = $user->getPanier();

        // Création et ajout d'un nouvel objet ContenuPanier
        $contenuPanier = new ContenuPanier();
        $contenuPanier->addProduit($produit);
        $contenuPanier->setQuantite(1);
        $contenuPanier->setDate(new \DateTime());
        $contenuPanier->setPanier($panier);

        $entityManager->persist($contenuPanier);
        $entityManager->flush();

        return $this->redirectToRoute('app_panier');
    }
}
