<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TailleBoissonRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TailleBoissonRepository::class)]
// #[ApiResource()]
class TailleBoisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups([
        'produit:write',"produit:read:all",
        // 'boisson:read:all','boisson:write',
        "menu:read:all",'menu:read:simple'
    ])]
    #[ORM\ManyToOne(targetEntity: Boisson::class, inversedBy: 'tailleBoissons')]
    private $boisson;

    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: 'tailleBoissons')]
    #[Groups([
        'produit:write',"produit:read:all",
        'boisson:read:all','boisson:write',
    ])]
    private $taille;

    #[ORM\Column(type: 'integer')]
    #[Groups([
        'produit:write',"produit:read:all",
        'boisson:read:all','boisson:write',
        "menu:read:all",'menu:read:simple'
    ])]
    private $stock;

    #[Groups([
        "boisson:read:all",'boisson:write',
        "produit:read:all",
        "menu:read:all",'menu:read:simple'
    ])]
    #[ORM\Column(type: 'float', nullable: true)]
    private $prixBoisson;

    #[ORM\ManyToMany(targetEntity: LigneDeCommande::class, inversedBy: 'tailleBoissons')]
    private $ligneDeCommande;

    public function __construct()
    {
        $this->ligneDeCmde = new ArrayCollection();
        $this->ligneDeCommande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoisson(): ?Boisson
    {
        return $this->boisson;
    }

    public function setBoisson(?Boisson $boisson): self
    {
        $this->boisson = $boisson;

        return $this;
    }

    public function getTaille(): ?Taille
    {
        return $this->taille;
    }

    public function setTaille(?Taille $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrixBoisson(): ?float
    {
        return $this->prixBoisson;
    }

    public function setPrixBoisson(?float $prixBoisson): self
    {
        $this->prixBoisson = $prixBoisson;

        return $this;
    }

    /**
     * @return Collection<int, LigneDeCommande>
     */
    public function getLigneDeCommande(): Collection
    {
        return $this->ligneDeCommande;
    }

    public function addLigneDeCommande(LigneDeCommande $ligneDeCommande): self
    {
        if (!$this->ligneDeCommande->contains($ligneDeCommande)) {
            $this->ligneDeCommande[] = $ligneDeCommande;
        }

        return $this;
    }

    public function removeLigneDeCommande(LigneDeCommande $ligneDeCommande): self
    {
        $this->ligneDeCommande->removeElement($ligneDeCommande);

        return $this;
    }

}
