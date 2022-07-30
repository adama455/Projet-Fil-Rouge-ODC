<?php

namespace App\Entity;

use App\Entity\Produit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[ApiResource(
    // attributes: [
    //     "pagination_enabled" => true,
    //     "pagination_items_per_page"=>5
    // ],
    //redefinition des ressources
    collectionOperations:[
        "get" =>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' =>['groups' => ['produit:read:simple']],
        ],
        "post"=> [
            'denormalization_context' => ['groups' => ["produit:write",]],
            'normalization_context' => ['groups' => ['produit:read:all']],
            "security_post_denormalize" => "is_granted('PRODUCT_CREAT', object)",
            "security_post_denormalize_message" => "Only gestionnaire can add burgers.",
            // 'normalization_context' =>['groups' => ['produit:write:simple']],
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
        ],
    ]
)]
class Burger extends Produit
{
    #[ORM\OneToMany(mappedBy: 'burger', targetEntity: MenuBurger::class)]
    private $menuBurgers;

    public function __construct()
    {
        parent::__construct();
        $this->menuBurgers = new ArrayCollection();
    }

    /**
     * @return Collection<int, MenuBurger>
     */
    public function getMenuBurgers(): Collection
    {
        return $this->menuBurgers;
    }

    public function addMenuBurger(MenuBurger $menuBurger): self
    {
        if (!$this->menuBurgers->contains($menuBurger)) {
            $this->menuBurgers[] = $menuBurger;
            $menuBurger->setBurger($this);
        }

        return $this;
    }

    public function removeMenuBurger(MenuBurger $menuBurger): self
    {
        if ($this->menuBurgers->removeElement($menuBurger)) {
            // set the owning side to null (unless already changed)
            if ($menuBurger->getBurger() === $this) {
                $menuBurger->setBurger(null);
            }
        }

        return $this;
    }
}
