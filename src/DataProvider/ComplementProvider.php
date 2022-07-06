<?php
// src/DataPersister/DataPersister.php

namespace App\DataProvider;

use App\Entity\Complements;
use App\Repository\BoissonRepository;
use App\Repository\FriteRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class ComplementProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    public function __construct(BoissonRepository $boissonR,FriteRepository $friteR) 
    {
        $this->boissonR = $boissonR;
        $this->friteR = $friteR;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCollection(string $ressourceClass, string $operationName=Null, array $context=[])
    {
        // dd($context[]=$this->boissonR->findAll());
        return $context=[
            $this->boissonR->findAll(),
            $this->friteR->findAll()                                                
        ];
    }
    
    public function supports(string $ressourceClass, string $operationName=Null, array $context=[]): bool
    {
        return $ressourceClass === Complements::class;
    }
}