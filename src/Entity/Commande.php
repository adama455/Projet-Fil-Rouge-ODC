<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Container6xaZIxQ\getResponseService;
// use App\Entity\Menu;
// use ApiPlatform\Core\Annotation\ApiSubresource;
// use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert; // Symfony's built-in constraints
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiResource(
    attributes: [
        "pagination_enabled" => true,
        "pagination_items_per_page"=>5
    ],
    collectionOperations:[
        "post"=>[
            "method" => "POST",
            'denormalization_context'=>['groups' => ['commande:write']],
            'normalization_context'=>['groups' => ['commande:read']],
            "security_post_denormalize" => "is_granted('COM_CREAT', object)",
            "security_post_denormalize_message" => "Only client can add commande.",
        ],
        "get"=>[
            'normalization_context'=>['groups' => ['commande:read:all']],
        ]
    ],
    itemOperations:["put","get"]
)]
class Commande
{
    #[ORM\Id]
    #[Groups(["commande:read",'livraison:write'])]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'commandes')]
    #[Groups(["commande:read",'commande:write'])]
    // #[Assert\NotNull(['message' => 'il faut une zone.'])]
    private $zone;

    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: 'commandes')]
    private $livraison;

    // #[ORM\OneToOne(targetEntity: Payement::class, cascade: ['persist', 'remove'])]
    // private $payement;
    #[ORM\Column(type: 'string', length: 100)] 
    #[Groups(["commande:read","commande:read:all"])]
    #[Assert\NotNull(['message' => 'numéro commande obligatoire.'])]
    private $reference;
    
    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    private $client;
    
    // #[ORM\Column(type: 'smallint', options:["default"=>1])] 
    // #[Groups(["commande:read"])]
    // private $etat;
    
    
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: LigneDeCommande::class,cascade:["persist"])]
    #[Groups(['commande:write','commande:read','commande:read:all'])]
    #[SerializedName("produits")]
    #[Assert\Count(min: 2, minMessage: 'Le menu doit contenir au moins 2 produit')]
    #[Assert\Valid]
    private $ligneDeCommandes;

    // #[ORM\Column(type: 'float', nullable: true)] 
    // #[Groups(["commande:read"])]
    // private $prixCommande;
    
    #[ORM\Column(type: 'datetime')]
    #[Groups(['commande:read','commande:read:all'])]
    private $dateCmde;

    #[ORM\Column(type: 'float', nullable: true)]
    private $remise=5/100;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['commande:read','commande:read:all'])]
    private $montantCommande;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $etat;

    public function __construct()
    {
        // $this->produits = new ArrayCollection();
        $this->ligneDeCommandes = new ArrayCollection();
        $this->etat="en attente";
        $this->reference = "CMD-".time();
        $this->dateCmde = new \DateTime();
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

    // public function getPayement(): ?Payement
    // {
    //     return $this->payement;
    // }

    // public function setPayement(?Payement $payement): self
    // {
    //     $this->payement = $payement;

    //     return $this;
    // }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    // public function getEtat(): ?int
    // {
    //     return $this->etat;
    // }

    // public function setEtat(int $etat): self
    // {
    //     $this->etat = $etat;

    //     return $this;
    // }

    // public function getPrixCommande(): ?float
    // {
    //     $prixTotalCmde = 0;
    //     foreach ($this->getLigneDeCommandes() as $ligneDeCommande) {
    //         $prixTotalCmde = $prixTotalCmde + $ligneDeCommande->getPrixLCmde();
    //     }
    //     return $this->prixCommande = round($prixTotalCmde * (1-$this->getRemise()));
    // }

    // public function setPrixCommande(?float $prixCommande): self
    // {
    //     $this->prixCommande = $prixCommande;

    //     return $this;
    // }

    /**
     * @return Collection<int, LigneDeCommande>
     */
    public function getLigneDeCommandes(): Collection
    {
        return $this->ligneDeCommandes;
    }

    public function addLigneDeCommande(LigneDeCommande $ligneDeCommande): self
    {
        if (!$this->ligneDeCommandes->contains($ligneDeCommande)) {
            $this->ligneDeCommandes[] = $ligneDeCommande;
            $ligneDeCommande->setCommande($this);
        }

        return $this;
    }

    public function removeLigneDeCommande(LigneDeCommande $ligneDeCommande): self
    {
        if ($this->ligneDeCommandes->removeElement($ligneDeCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneDeCommande->getCommande() === $this) {
                $ligneDeCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getDateCmde(): ?\DateTimeInterface
    {
        return $this->dateCmde;
    }

    public function setDateCmde(\DateTimeInterface $dateCmde): self
    {
        $this->dateCmde = $dateCmde;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(?float $remise): self
    {
        $this->remise = $remise;

        return $this;
    }

    public function getMontantCommande(): ?float
    {
        return $this->montantCommande;
        // $montantCmde = 0;
        // foreach ($this->getLigneDeCommandes() as $ligneDeCommande) {
        //     $montantCmde = $montantCmde + $ligneDeCommande->getPrixLCmde();
        // }
        // return $this->montantCommande = round($montantCmde * (1-$this->getRemise()));
    }

    public function setMontantCommande(?float $montantCommande): self
    {
        $this->montantCommande = $montantCommande;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

}
