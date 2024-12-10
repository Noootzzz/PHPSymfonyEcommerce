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

    #[Route('/produit/delete/{id}', name: 'app_delete_produit')]
    public function delete(Request $request, EntityManagerInterface $em, Produit $produit)
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('csrf'))) {
            $em->remove($produit);
            $em->flush();
        }
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/produit/edit/{id}', name: 'app_edit_produit')]
    public function edit(Request $request , ProduitRepository $produitRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        // Récupérer le produit par son ID
        $product = $produitRepository->find($id);

        // Créer et gérer le formulaire
        $form = $this->createForm(ProduitType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product); // Persiste les modifications
            $entityManager->flush();          // Sauvegarde dans la base de données

            // Rediriger vers une autre page
            return $this->redirectToRoute('app_produit', ['id' => $product->getId()]);
        }

        // Rendre la vue avec le formulaire
        return $this->render('accueil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
