<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BoissonRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;

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
            'normalization_context' =>['groups' => ['burger:read:simple']],
        ],
        "post"=> [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Seul les Gestionnaires peuvent Ajouter des Boissons."
        ],
    ],

    itemOperations:[
        "put"=> [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Seul les Gestionnaires peuvent Modifier des Boissons."
        ]

        ,"get"
    ]
)]
class Boisson extends Complement
{
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $taille;

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): self
    {
        $this->taille = $taille;

        return $this;
    }
}
