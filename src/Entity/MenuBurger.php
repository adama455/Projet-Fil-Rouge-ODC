<?php

namespace App\Entity;

use App\Entity\Menu;
use App\Entity\Burger;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuBurgerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert; // Symfony's built-in constraints


#[ORM\Entity(repositoryClass: MenuBurgerRepository::class)]
#[ApiResource()]
class MenuBurger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]  
    #[Groups([
        "menu:write","produit:read:all",
        "produit:read:simple",
        "menu:read:all",'menu:read:simple'
    ])]
    private $quatite;

    #[Assert\NotNull(['message' => 'il faut au moins un burger .'])]
    #[ORM\ManyToOne(targetEntity: Burger::class, inversedBy: 'menuBurgers')]
    #[Groups([
        'menu:write',"produit:read:all",
        "produit:read:simple",
        "menu:read:all",'menu:read:simple'
    ])]
    private $burger;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuBurgers')]
    private $menu;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuatite(): ?int
    {
        return $this->quatite;
    }

    public function setQuatite(?int $quatite): self
    {
        $this->quatite = $quatite;

        return $this;
    }

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): self
    {
        $this->burger = $burger;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }
}
