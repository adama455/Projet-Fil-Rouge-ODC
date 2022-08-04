<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuFriteRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuFriteRepository::class)]
#[ApiResource()]
class MenuFrite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuFrites')]
    private $menu;
    
    #[ORM\ManyToOne(targetEntity: Frite::class, inversedBy: 'menuFrites')]
    #[Groups([
        'menu:write',"produit:read:all",
        "produit:read:simple",
        "menu:read:all",'menu:read:simple'
    ])]
    private $frite;
    
    #[ORM\Column(type: 'integer')]
    #[Groups([
        'menu:write',"produit:read:all",
        "produit:read:simple",
        "menu:read:all",'menu:read:simple'
    ])]
    private $quantite;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFrite(): ?Frite
    {
        return $this->frite;
    }

    public function setFrite(?Frite $frite): self
    {
        $this->frite = $frite;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
}
