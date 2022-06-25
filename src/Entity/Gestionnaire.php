<?php

namespace App\Entity;

use App\Entity\User;
// use PhpParser\Builder\Use_;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GestionnaireRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Entity(repositoryClass: GestionnaireRepository::class)]
#[ApiResource(
    attributes: [
        "pagination_enabled" => true,
        "pagination_items_per_page"=>5
    ],
    collectionOperations:[
        "get" =>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' =>['groups' => ['user:read:simple']],
        ],

        "post"],

    itemOperations:["put","get"]
)]
class Gestionnaire extends User
{
    // public function __construct(){
    //     $this->roles=["ROLES_GESTIONNAIRE"];
    // }
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column(type: 'integer')]
    // private $id;

    // public function getId(): ?int
    // {
    //     return $this->id;
    // }
}
