<?php

namespace App\Entity;

use App\Entity\Zone;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuartierRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert; // Symfony's built-in constraints

#[ORM\Entity(repositoryClass: QuartierRepository::class)]
#[ApiResource(
    attributes: [
        "pagination_enabled" => true,
        "pagination_items_per_page"=>5
    ],
    collectionOperations:[
        "get" =>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' =>['groups' => ['user:read:simple']]
        ],
        "post"=>[
            'denormalization_context' =>['groups' => ['user:write']],
            'normalization_context' =>['groups' => ['user:read:all']],
            "security_post_denormalize" => "is_granted('POST_CREAT', object)",
            "security_post_denormalize_message" => "Only gestionnaire can add quartier.",
        ]
    ],
    itemOperations:[
        "put"=>[
            "security" => "is_granted('POST_EDIT', object)",
            "security" => "Only gestionnaire can edit quartier.",
        ],
        "get"
    ]
)]
class Quartier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['user:read:simple','user:read:all',])]
    #[ORM\Column(type: 'integer')]
    private $id;
    
    #[Assert\NotBlank(['message' => 'nom est obligatoire',])]
    #[Groups(['user:read:simple','user:write','user:read:all'])]
    #[ORM\Column(type: 'string', length: 100)]
    private $nom;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[Assert\NotBlank(['message' => 'la rue est obligatoire',])]
    #[Groups(['user:read:simple','user:write','user:read:all'])]
    private $rue;

    #[ORM\Column(type: 'smallint', options:["default"=>1])]
    #[Groups(['user:read:simple','user:read:all'])]
    private $etat;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'quartiers')]
    private $zone;

    public function __construct()
    {
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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): self
    {
        $this->rue = $rue;

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

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }
}
