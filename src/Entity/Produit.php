<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Mapping\ClassMetadata;
// use App\Validator\Constraints\MinimalProperties; // A custom constraint
use Symfony\Component\Serializer\Annotation\Groups;
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
            'denormalization_context' => ['groups' => ['menu:write']],
            'normalization_context' =>['groups' => ['produit:Write:simple']],
            "security_post_denormalize" => "is_granted('PRODUCT_CREAT', object)",
            "security_post_denormalize_message" => "Only admins can add books.",
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
    #[Groups(["menu:write","produit:read:simple","produit:read:all" ])]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[Groups(["produit:read:simple","produit:write","produit:read:all","boisson" ])]
    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(['message' => 'le nom est obligatoire',])]
    protected $nom;

    #[Groups(["produit:read:simple","produit:write","produit:read:all"])]
    #[Assert\NotBlank(['message' => 'le prix est obligatoire',])]
    #[ORM\Column(type: 'float')]
    protected $prix;

    #[ORM\Column(type: 'smallint',options:["default"=>1])]
    protected $etat;

    // #[Assert\NotBlank()]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'produits')]
    private $user;

    #[Groups(["produit:write","produit:read:simple"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    // #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'produits')]
    // #[ApiSubresource()]
    // private $commandes;

    #[ORM\Column(type: 'object', nullable: true)]
    protected $image;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: LigneDeCommande::class)]
    private $ligneDeCommandes;

    public function __construct(File $file = null)
    {
        // $this->commandes = new ArrayCollection();
        $this->etat = 1;
        $this->file = $file;
        $this->ligneDeCommandes = new ArrayCollection();
    }

    #[Groups(["produit:read:simple","write" ])]


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

    // /**
    //  * @return Collection<int, Commande>
    //  */
    // public function getCommandes(): Collection
    // {
    //     return $this->commandes;
    // }

    // public function addCommande(Commande $commande): self
    // {
    //     if (!$this->commandes->contains($commande)) {
    //         $this->commandes[] = $commande;
    //         $commande->addProduit($this);
    //     }

    //     return $this;
    // }

    // public function removeCommande(Commande $commande): self
    // {
    //     if ($this->commandes->removeElement($commande)) {
    //         $commande->removeProduit($this);
    //     }

    //     return $this;
    // }

    public function getImage(): ?object
    {
        return $this->image;
    }

    public function setImage(?object $image): self
    {
        $this->image = $image;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('image', new Assert\Image([
            'minWidth' => 200,
            'maxWidth' => 400,
            'minHeight' => 200,
            'maxHeight' => 400,
        ]));

    }

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
            $ligneDeCommande->setProduit($this);
        }

        return $this;
    }

    public function removeLigneDeCommande(LigneDeCommande $ligneDeCommande): self
    {
        if ($this->ligneDeCommandes->removeElement($ligneDeCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneDeCommande->getProduit() === $this) {
                $ligneDeCommande->setProduit(null);
            }
        }

        return $this;
    }

}
