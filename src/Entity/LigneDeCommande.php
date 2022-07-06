<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
// use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LigneDeCommandeRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LigneDeCommandeRepository::class)]
// #[ApiResource()]
class LigneDeCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(["commande:write"])]
    #[ORM\Column(type: 'integer')]
    private $quantiteCmde;

    #[Groups(["commande:write"])]
    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'ligneDeCommandes')]
    private $produit;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'ligneDeCommandes')]
    private $commande;

    #[ORM\Column(type: 'float')]
    private $prixLCmde;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
    public function prixLCommande(){
        return $this->produit->getPrix()*$this->quantiteProduit;
    }

    public function getPrixLCmde(): ?float
    {
        return $this->prixLCmde;
    }

    public function setPrixLCmde(float $prixLCmde): self
    {
        $this->prixLCmde = $prixLCmde;

        return $this;
    }

    public function getQuantiteCmde(): ?int
    {
        return $this->quantiteCmde;
    }

    public function setQuantiteCmde(int $quantiteCmde): self
    {
        $this->quantiteCmde = $quantiteCmde;

        return $this;
    }
}
