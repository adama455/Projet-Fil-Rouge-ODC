<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
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
class Client extends User
{
    #[ORM\Column(type: 'string', length: 100)]
    private $adresse;

    // public function __construct(){
    //     $this->roles=["ROLES_CLIENT"];
    // }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
}
