<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(ProduitRepository $produitRepository, EntityManagerInterface $em, Request $request): Response
    {
        // Récupère tous les produits
        $produits = $produitRepository->findAllProduits();

        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('Photo')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', "Impossible d'ajouter l'image");
                    return $this->redirectToRoute('app_accueil');
                }

                $produit->setPhoto($newFilename);
            }

            $em->persist($produit);
            $em->flush();
            $this->addFlash('success', 'Produit ajouté');
            return $this->redirectToRoute('app_accueil');
        }

        // Passe les produits à la vue
        return $this->render('accueil/index.html.twig', [
            'produits' => $produits, // Données à envoyer à Twig
            'ajout_produit' => $form->createView(),
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit')]
    public function show(ProduitRepository $produitRepository, int $id): Response
    {
        $produit = $produitRepository->findProduitById($id);
        return $this->render('accueil/produit.html.twig', [
            "produit" => $produit,
        ]);
    }
}
