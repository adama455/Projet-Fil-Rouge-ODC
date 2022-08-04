<?php
namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
#[ApiResource(
    attributes: [
        "pagination_enabled" => true,
        "pagination_items_per_page"=>5
    ],
    collectionOperations:[
        "complements"=>[
            "method"=>"GET",
            "path"=>"/complements",
            'normalization_context'=>['groups' => ['complement:read:all']]
        ],

        "GET"=>[
            'normalization_context'=>['groups' => ['complement:read:all']]
        ]
    ],
    itemOperations:[
        
    ]
)]
class Complements
{
    #[ApiProperty(
        identifier:true
    )]
    private $id;
}
