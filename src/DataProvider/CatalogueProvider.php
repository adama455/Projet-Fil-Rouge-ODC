<?php
// src/DataPersister/DataPersister.php

namespace App\DataProvider;

use App\Entity\Catalogue;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class CatalogueProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    public function __construct(BurgerRepository $burgerR,MenuRepository $menuR) 
    {
        $this->burgerR = $burgerR;
        $this->menuR = $menuR;
    }
    
    /**
     * {@inheritdoc}    
    */
    public function getCollection(string $ressourceClass, string $operationName=Null, array $context=[])
    {
        // dd($context[]=$this->burgerR->findAll());
        return $context=[
            "burgers" => $this->burgerR->findByEtat(1),
            "menus" =>$this->menuR->findByEtat(1)

        ];

    }
    
    public function supports(string $ressourceClass, string $operationName=Null, array $context=[]): bool
    {
        return $ressourceClass=== Catalogue::class;
    }
}