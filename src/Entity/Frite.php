<?php

namespace App\Entity;

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
            'normalization_context' =>['groups' => ['burger:read:simple']],
        ],
        "post"=> [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Seul les Gestionnaires peuvent Ajouter des Frites."
        ],
    ],

    itemOperations:[
        "put"=> [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Seul les Gestionnaires peuvent Modifier des Frites."
        ]

        ,"get"
    ]
)]
class Frite extends Complement
{
    #[ORM\Column(type: 'integer')]
    private $quantite;

    // #[ORM\Column(type: 'string', length: 100, nullable: false)]
    // private $portion;

    // public function getPortion(): ?string
    // {
    //     return $this->portion;
    // }

    // public function setPortion(?string $portion): self
    // {
    //     $this->portion = $portion;

    //     return $this;
    // }

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
