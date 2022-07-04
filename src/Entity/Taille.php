<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TailleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert; // Symfony's built-in constraints
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TailleRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get",

        "post" => [
            "security_post_denormalize" => "is_granted('POST_CREAT', object)",
            "security_post_denormalize_message" => "Only gestionnaire can add taille.",
        ],
    ],

    itemOperations:[
        "put"=> [
            "security" => "is_granted('POST_EDIT', object)",
            "security" => "Only gestionnaire can edit taille.",
        ],
    
        "get"
    ]
)]
class Taille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotNull(['message' => 'nom taille obligatoire.'])]
    #[ORM\Column(type: 'string', length: 100)]
    private $nom;

    #[ORM\Column(type: 'smallint',options:["default"=>1])]
    private $etat;

    #[ORM\ManyToMany(targetEntity: Boisson::class, inversedBy: 'tailles')]
    private $boissons;

    public function __construct()
    {
        $this->boissons = new ArrayCollection();
        $this->etat=1;
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
}
