<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BurgerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


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
            "security_post_denormalize" => "is_granted('PRODUCT_CREAT', object)",
            "security_post_denormalize_message" => "Only gestionnaire can add menus.",
        ],
    ],

    itemOperations:[
        "put"=> [
            "security" => "is_granted('PRODUCT_EDIT', object)",
            "security_message" => "Only gestionnaire can edit frite.",
        ],

        "get"
    ]
)]
class Menu extends Produit
{
    #[ORM\ManyToMany(targetEntity: Burger::class, inversedBy: 'menus')]
    #[Assert\NotBlank(['message' => 'un burger est obligatoire',])]
    private $burgers;

    #[ORM\ManyToMany(targetEntity: Boisson::class, inversedBy: 'menus')]
    #[Assert\NotBlank(['message' => 'boisson est obligatoire',])]
    private $boissons;

    #[ORM\ManyToMany(targetEntity: Frite::class, inversedBy: 'menus')]
    private $frites;

    public function __construct()
    {
        parent::__construct();
        $this->burgers = new ArrayCollection();
        $this->boissons = new ArrayCollection();
        $this->frites = new ArrayCollection();
    }

    /**
     * @return Collection<int, Burger>
     */
    public function getBurgers(): Collection
    {
        return $this->burgers;
    }

    public function addBurger(Burger $burger): self
    {
        if (!$this->burgers->contains($burger)) {
            $this->burgers[] = $burger;
        }

        return $this;
    }

    public function removeBurger(Burger $burger): self
    {
        $this->burgers->removeElement($burger);

        return $this;
    }

    /**
     * @return Collection<int, Boisson>
     */
    public function getBoissons(): Collection
    {
        return $this->boissons;
    }

    public function addBoisson(Boisson $boisson): self
    {
        if (!$this->boissons->contains($boisson)) {
            $this->boissons[] = $boisson;
        }

        return $this;
    }

    public function removeBoisson(Boisson $boisson): self
    {
        $this->boissons->removeElement($boisson);

        return $this;
    }

    /**
     * @return Collection<int, Frite>
     */
    public function getFrites(): Collection
    {
        return $this->frites;
    }

    public function addFrite(Frite $frite): self
    {
        if (!$this->frites->contains($frite)) {
            $this->frites[] = $frite;
        }

        return $this;
    }

    public function removeFrite(Frite $frite): self
    {
        $this->frites->removeElement($frite);

        return $this;
    }


    public function totalBurger($prixT){
        $prix=0;
        $burgers=$prixT->getBurgers();
        foreach ($burgers as $burger ){
            $prix+=$burger->getPrix();
        }
    }

    public function totalFrite($prixT){
        $prix=0;
        $frites=$prixT->getFrites();
        foreach ($frites as $frite ){
            $prix+=$frite->getPrix();
        }
    }
    public function totalBoisson($prixT){
        $prix=0;
        $boissons=$prixT->getBoissons();
        foreach ($boissons as $boisson ){
            $prix+=$boisson->getPrix();
        }
    }

    public function prixMenu($menuComde){
        $totalBurger=$menuComde->totalBurger($menuComde);
        $totalBoisson=$menuComde->totalBoisson($menuComde);
        $totalFrite=$menuComde->totalFrite($menuComde);
        return $totalBurger+$totalBoisson+$totalFrite;
    }
}
