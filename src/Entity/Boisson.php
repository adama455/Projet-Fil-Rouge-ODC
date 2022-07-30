<?php

namespace App\Entity;

use App\Entity\Menu;
use App\Entity\Taille;
use App\Entity\TailleBoisson;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BoissonRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert; // Symfony's built-in constraints


#[ORM\Entity(repositoryClass: BoissonRepository::class)]
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
        "post"=> [
            // 'normalization_context' =>['groups' => ['produit:write:simple']],
            'denormalization_context' => ['groups' => ['boisson:write']],
            'normalization_context' => ['groups' => ['boisson:read:all']],
            "security_post_denormalize" => "is_granted('PRODUCT_CREAT', object)",
            "security_post_denormalize_message" => "Only gestionnaire can add boissons."
        ],
    ],

    itemOperations:[
        "put"=> [
            'denormalization_context' => ['groups' => ['write']],
            "security" => "is_granted('PRODUCT_EDIT', object)",
            "security_message" => "Only gestionnaire can edit boisson.",
        ],

        "get" =>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' =>['groups' => ['produit:read:simple']],
        ]
    ]
)]
class Boisson extends produit
{
    #[ORM\OneToMany(mappedBy: 'boisson', targetEntity: TailleBoisson::class,cascade:['persist'])]
    #[Groups(["boisson:write","boisson:read:all"])]
    #[SerializedName("tailles")]
    private $tailleBoissons;

    // #[Assert\NotNull(['message' => 'il faut une taille pour un boisson.'])]
    // #[ORM\ManyToMany(targetEntity: Taille::class, mappedBy: 'boissons')]
    // private $tailles;

    public function __construct()
    {
        parent::__construct();
        // $this->tailles = new ArrayCollection();
        // $this->etat = 1;
        $this->prix=0;
        $this->tailleBoissons = new ArrayCollection();
    }

    // /**
    //  * @return Collection<int, Taille>
    //  */
    // public function getTailles(): Collection
    // {
    //     return $this->tailles;
    // }

    // public function addTaille(Taille $taille): self
    // {
    //     if (!$this->tailles->contains($taille)) {
    //         $this->tailles[] = $taille;
    //         $taille->addBoisson($this);
    //     }

    //     return $this;
    // }

    // public function removeTaille(Taille $taille): self
    // {
    //     if ($this->tailles->removeElement($taille)) {
    //         $taille->removeBoisson($this);
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, Menu>
    //  */
    // public function getMenus(): Collection
    // {
    //     return $this->menus;
    // }

    // public function addMenu(Menu $menu): self
    // {
    //     if (!$this->menus->contains($menu)) {
    //         $this->menus[] = $menu;
    //         $menu->addBoisson($this);
    //     }

    //     return $this;
    // }

    // public function removeMenu(Menu $menu): self
    // {
    //     if ($this->menus->removeElement($menu)) {
    //         $menu->removeBoisson($this);
    //     }

    //     return $this;
    // }

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
            $tailleBoisson->setBoisson($this);
        }

        return $this;
    }

    public function removeTailleBoisson(TailleBoisson $tailleBoisson): self
    {
        if ($this->tailleBoissons->removeElement($tailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($tailleBoisson->getBoisson() === $this) {
                $tailleBoisson->setBoisson(null);
            }
        }

        return $this;
    }
    // public function getPrix(): ?float
    // {
    //     foreach ($this->getTailleBoissons() as $tailleBoisson) {
    //         $prixb = $tailleBoisson->getPrixBoisson() * getTailleBoissons();
    //     }
    //     return $this->prix = $this->$prixb * ;
    // }
}
