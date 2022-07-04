<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert; // Symfony's built-in constraints
use Doctrine\Common\Collections\ArrayCollection;
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiResource(
    attributes: [
        "pagination_enabled" => true,
        "pagination_items_per_page"=>5
    ],
    collectionOperations:[
        "post"=>[
            "security_post_denormalize" => "is_granted('COM_CREAT', object)",
            "security_post_denormalize_message" => "Only client can add commande.",
        ],
        "get"
    ],
    itemOperations:["put","get"]
)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'commandes')]
    #[Assert\NotNull(['message' => 'il faut une zone.'])]
    private $zone;

    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: 'commandes')]
    private $livraison;

    #[ORM\OneToOne(targetEntity: Payement::class, cascade: ['persist', 'remove'])]
    private $payement;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    private $client;

    #[ORM\Column(type: 'datetime')]
    private $date;
    
    #[ORM\Column(type: 'smallint', options:["default"=>1])]
    private $etat;
    
    
    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull(['message' => 'numéro commande obligatoire.'])]
    private $numero;

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'commandes')]
    #[Assert\NotNull(['message' => 'il faut au moins un produit.'])]
    #[ApiSubresource()]
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->etat=1;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getPayement(): ?Payement
    {
        return $this->payement;
    }

    public function setPayement(?Payement $payement): self
    {
        $this->payement = $payement;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        $this->produits->removeElement($produit);

        return $this;
    }
}
