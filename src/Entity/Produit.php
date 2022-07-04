<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
// use App\Validator\Constraints\MinimalProperties; // A custom constraint
use Symfony\Component\Validator\Constraints as Assert; // Symfony's built-in constraints

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type",type: "string")]
#[ORM\DiscriminatorMap(["burger" => "Burger","menu"=>"Menu","boisson"=>"Boisson","frite"=>"Frite"])]
#[ApiResource(
    attributes: [
        "pagination_enabled" => true,
        "pagination_items_per_page"=>5
    ],
    //redefinition des ressources
    collectionOperations:[
        "get" =>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' =>['groups' => ['produit:read:simple']],
        ],

        "post"=>[
            // "security_post_denormalize" => "is_granted('PRODUCT_CREAT', object)",
            // "security_post_denormalize_message" => "Only admins can add books.",
            'normalization_context' =>['groups' => ['produit:Write:simple']],
        ]
    ],
    itemOperations:[
        "put"=> [
            // "security_post_denormalize" => "is_granted('PRODUCT_EDIT', object)",
            // "security_post_denormalize_message" => "Only admins can add books.",
        ],
        
        "get"
    ]
)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["produit:read:simple","produit:Write:simple" ])]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[Groups(["produit:read:simple","produit:Write:simple" ])]
    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(['message' => 'le nom est obligatoire',])]
    protected $nom;

    #[Groups(["produit:read:simple","produit:Write:simple" ])]
    #[Assert\NotBlank(['message' => 'le prix est obligatoire',])]
    #[ORM\Column(type: 'float')]
    protected $prix;

    #[ORM\Column(type: 'smallint',options:["default"=>1])]
    protected $etat;

    // #[Assert\NotBlank()]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'produits')]
    private $user;

    #[Groups(["produit:read:simple","produit:Write:simple" ])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'produits')]
    #[ApiSubresource()]
    private $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->etat = 1;
    }

    #[Groups(["produit:read:simple","produit:Write:simple" ])]


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->addProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            $commande->removeProduit($this);
        }

        return $this;
    }

}
