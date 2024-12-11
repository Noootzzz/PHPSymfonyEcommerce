<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'panier', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $Utilisateur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateAchat = null;

    #[ORM\Column]
    private ?bool $Etat = false;

    #[ORM\OneToOne(mappedBy: 'Panier', cascade: ['persist', 'remove'])]
    private ?ContenuPanier $contenuPanier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->Utilisateur;
    }

    public function setUtilisateur(Utilisateur $Utilisateur): static
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->DateAchat;
    }

    public function setDateAchat(?\DateTimeInterface $DateAchat): static
    {
        $this->DateAchat = $DateAchat;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->Etat;
    }

    public function setEtat(bool $Etat): static
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getContenuPanier(): ?ContenuPanier
    {
        return $this->contenuPanier;
    }

    public function setContenuPanier(?ContenuPanier $contenuPanier): static
    {
        // unset the owning side of the relation if necessary
        if ($contenuPanier === null && $this->contenuPanier !== null) {
            $this->contenuPanier->setPanier(null);
        }

        // set the owning side of the relation if necessary
        if ($contenuPanier !== null && $contenuPanier->getPanier() !== $this) {
            $contenuPanier->setPanier($this);
        }

        $this->contenuPanier = $contenuPanier;

        return $this;
    }
}
