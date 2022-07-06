<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FriteRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Entity(repositoryClass: FriteRepository::class)]
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
            'denormalization_context' => ['groups' => ['produit:write']],
            'normalization_context' => ['groups' => ['produit:read:all']],
            "security_post_denormalize" => "is_granted('PRODUCT_CREAT', object)",
            "security_post_denormalize_message" => "Only gestionnaire can add frites.",
        ],
    ],

    itemOperations:[
        "put"=> [
            'denormalization_context' => ['groups' => ['write']],
            "security" => "is_granted('PRODUCT_EDIT', object)",
            "security_message" => "Only gestionnaire can edit frite.",
        ],

        "get" =>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' =>['groups' => ['produit:read:simple']],
        ]
    ]
)]
class Frite extends Produit
{
    #[ORM\OneToMany(mappedBy: 'frite', targetEntity: MenuFrite::class)]
    private $menuFrites;

    public function __construct()
    {
        parent::__construct();
        $this->menus = new ArrayCollection();
        $this->menuFrites = new ArrayCollection();
    }

    /**
     * @return Collection<int, MenuFrite>
     */
    public function getMenuFrites(): Collection
    {
        return $this->menuFrites;
    }

    public function addMenuFrite(MenuFrite $menuFrite): self
    {
        if (!$this->menuFrites->contains($menuFrite)) {
            $this->menuFrites[] = $menuFrite;
            $menuFrite->setFrite($this);
        }

        return $this;
    }

    public function removeMenuFrite(MenuFrite $menuFrite): self
    {
        if ($this->menuFrites->removeElement($menuFrite)) {
            // set the owning side to null (unless already changed)
            if ($menuFrite->getFrite() === $this) {
                $menuFrite->setFrite(null);
            }
        }

        return $this;
    }

}
