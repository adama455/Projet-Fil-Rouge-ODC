<?php

namespace App\Entity;

use App\Entity\Produit;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[ApiResource(
    //redefinition des ressources
    collectionOperations:[
        "get" =>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' =>['groups' => ['burger:read:simple']],
        ],

        "post" => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Seul les Gestionnaires peuvent Ajouter des Burgers."
        ],

    ],

    itemOperations:[
        "put"=> [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Seul les Gestionnaires peuvent Modifier des Burgers."
        ],
    
        "get"
    ]
)]
class Burger extends Produit
{
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column(type: 'integer')]
    // private $id;

    // public function getId(): ?int
    // {
    //     return $this->id;
    // }
}
