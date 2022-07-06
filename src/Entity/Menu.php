<?php

namespace App\Entity;

use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;


#[ORM\Entity(repositoryClass: MenuRepository::class)]
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
            'denormalization_context' => ['groups' => ['menu:write']],
            'normalization_context' => ['groups' => ['produit:read:all']],
            "security_post_denormalize" => "is_granted('PRODUCT_CREAT', object)",
            "security_post_denormalize_message" => "Only gestionnaire can add menus.",
        ],
    ],

    itemOperations:[
        "put"=> [
            'denormalization_context' => ['groups' => ['write']],
            'normalization_context' => ['groups' => ['produit:read:simple']],
            "security" => "is_granted('PRODUCT_EDIT', object)",
            "security_message" => "Only gestionnaire can edit frite.",
        ],

        "get"=>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' =>['groups' => ['produit:read:simple']],
        ]
    ]
)]
class Menu extends Produit
{
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuBurger::class,cascade:['persist'])]
    #[Groups(['menu:write',"produit:read:all","produit:read:simple"])]
    #[SerializedName("burgers")]
    private $menuBurgers;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuTaille::class,cascade:['persist'])]
    #[Groups(['menu:write',"produit:read:all","produit:read:simple"])]
    #[SerializedName("tailles")]
    private $menuTailles;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuFrite::class,cascade:['persist'])]
    #[Groups(['menu:write',"produit:read:all","produit:read:simple"])]
    #[SerializedName("frites")]
    private $menuFrites;

    public function __construct()
    {
        parent::__construct();
        $this->menuBurgers = new ArrayCollection();
        $this->menuTailles = new ArrayCollection();
        $this->menuFrites = new ArrayCollection();
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
            $menuBurger->setMenu($this);
        }

        return $this;
    }

    public function removeMenuBurger(MenuBurger $menuBurger): self
    {
        if ($this->menuBurgers->removeElement($menuBurger)) {
            // set the owning side to null (unless already changed)
            if ($menuBurger->getMenu() === $this) {
                $menuBurger->setMenu(null);
            }
        }

        return $this;
    }

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
            $menuTaille->setMenu($this);
        }

        return $this;
    }

    public function removeMenuTaille(MenuTaille $menuTaille): self
    {
        if ($this->menuTailles->removeElement($menuTaille)) {
            // set the owning side to null (unless already changed)
            if ($menuTaille->getMenu() === $this) {
                $menuTaille->setMenu(null);
            }
        }

        return $this;
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
            $menuFrite->setMenu($this);
        }

        return $this;
    }

    public function removeMenuFrite(MenuFrite $menuFrite): self
    {
        if ($this->menuFrites->removeElement($menuFrite)) {
            // set the owning side to null (unless already changed)
            if ($menuFrite->getMenu() === $this) {
                $menuFrite->setMenu(null);
            }
        }

        return $this;
    }
}
