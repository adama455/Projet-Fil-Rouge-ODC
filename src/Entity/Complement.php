<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ComplementRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Entity(repositoryClass: ComplementRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type",type: "string")]
#[ORM\DiscriminatorMap(["frite" => "Frite","boisson" => "Boisson"])]
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

        "post"],

    itemOperations:["put","get"]
)]
class Complement extends Produit
{
    
}
