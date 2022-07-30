<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TailleRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert; // Symfony's built-in constraints

#[ORM\Entity(repositoryClass: TailleRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get",

        "post" => [
            'denormalization_context' => ['groups' => ['taille:write']],
            'normalization_context' => ['groups' => ['taille:read:all']],
            "security_post_denormalize" => "is_granted('POST_CREAT', object)",
            "security_post_denormalize_message" => "Only gestionnaire can add taille.",
        ],
    ],

    itemOperations:[
        "put"=> [
            "security" => "is_granted('POST_EDIT', object)",
            "security" => "Only gestionnaire can edit taille.",
            'denormalization_context' => ['groups' => ['write']],
        ],
        
        "get"
        ]
        )]
class Taille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["taille:read:all",'taille:write'])]
    private $id;

    #[Assert\NotNull(['message' => 'nom taille obligatoire.'])]
    #[ORM\Column(type: 'string', length: 100)]
    #[Groups([
        "taille:read:all",'taille:write',
        "produit:read:all",
        // 'menu:read:simple'
    ])]
    private $nom;

    #[ORM\Column(type: 'smallint',options:["default"=>1])]
    private $etat;

    #[ORM\ManyToMany(targetEntity: Boisson::class, inversedBy: 'tailles')]
    private $boissons;

    // #[Groups(["produit:read:all"])]
    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: MenuTaille::class)]
    private $menuTailles;

    #[Groups([
        "produit:read:all",
        "menu:read:all",'menu:read:simple'
        ])]
    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: TailleBoisson::class)]
    private $tailleBoissons;

    // #[ORM\Column(type: 'integer')]
    // #[Groups(["menu:write","produit:read:all"])]
    // private $prix;

    public function __construct()
    {
        $this->etat=1;
        $this->menus = new ArrayCollection();
        $this->menuTailles = new ArrayCollection();
        $this->tailleBoissons = new ArrayCollection();
    }

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

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    // /**
    //  * @return Collection<int, Boisson>
    //  */
    // public function getBoissons(): Collection
    // {
    //     return $this->boissons;
    // }

    // public function addBoisson(Boisson $boisson): self
    // {
    //     if (!$this->boissons->contains($boisson)) {
    //         $this->boissons[] = $boisson;
    //     }

    //     return $this;
    // }

    // public function removeBoisson(Boisson $boisson): self
    // {
    //     $this->boissons->removeElement($boisson);

    //     return $this;
    // }

    /**
     * @return Collection<int, MenuTaille>
     */
    public function getMenuTailles(): Collection
    {
        return $this->menuTailles;
    }

    public function addMenuTaille(MenuTaille $menuTaille): self
    {
        if (!$this->menuTailles->contains($menuTaille)) {
            $this->menuTailles[] = $menuTaille;
            $menuTaille->setTaille($this);
        }

        return $this;
    }

    public function removeMenuTaille(MenuTaille $menuTaille): self
    {
        if ($this->menuTailles->removeElement($menuTaille)) {
            // set the owning side to null (unless already changed)
            if ($menuTaille->getTaille() === $this) {
                $menuTaille->setTaille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TailleBoisson>
     */
    public function getTailleBoissons(): Collection
    {
        return $this->tailleBoissons;
    }

    public function addTailleBoisson(TailleBoisson $tailleBoisson): self
    {
        if (!$this->tailleBoissons->contains($tailleBoisson)) {
            $this->tailleBoissons[] = $tailleBoisson;
            $tailleBoisson->setTaille($this);
        }

        return $this;
    }

    public function removeTailleBoisson(TailleBoisson $tailleBoisson): self
    {
        if ($this->tailleBoissons->removeElement($tailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($tailleBoisson->getTaille() === $this) {
                $tailleBoisson->setTaille(null);
            }
        }

        return $this;
    }

    // public function getPrix(): ?int
    // {
    //     return $this->prix;
    // }

    // public function setPrix(int $prix): self
    // {
    //     $this->prix = $prix;

    //     return $this;
    // }
}
