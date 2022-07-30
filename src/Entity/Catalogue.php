<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\DBAL\Schema\Identifier;

#[ApiResource(
    attributes: [
        "pagination_enabled" => true,
        "pagination_items_per_page"=>5
    ],
    collectionOperations:[
        "catalogue"=>[
            "method"=>"GET",
            "path"=>"/catalogues",
            'normalization_context'=>['groups' => ['produit:read:all']]
        ],
        "GET"=>[
            'normalization_context'=>['groups' => ['catalogue:read:all']]

        ]
    ],
    itemOperations:[

    ]
)]

class Catalogue
{
    #[ApiProperty(
        identifier:true
    )]
    private $id;
}
