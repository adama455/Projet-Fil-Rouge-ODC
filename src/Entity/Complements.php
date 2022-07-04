<?php
namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
#[ApiResource(
    attributes: [
        "pagination_enabled" => true,
        "pagination_items_per_page"=>5
    ],
    collectionOperations:[
        "complements"=>[
            "method"=>"GET",
            "path"=>"/complements"
    
        ]
    ],
    itemOperations:[]
)]
class Complements
{
    
}
