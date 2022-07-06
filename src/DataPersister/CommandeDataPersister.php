<?php
// src/DataPersister/DataPersister.php

namespace App\DataPersister;

// use App\Entity\User;
// use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

use App\Entity\Commande;
use App\Services\PrixCommande;
use App\Entity\LigneDeCommande;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 *
 */
class CommandeDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private ?TokenInterface $token;

    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $token,
        
    ) {
        $this->entityManager = $entityManager;
        $this->token = $token->getToken();
    }
    public function supports($data): bool
    {
        return $data instanceof Commande;
    }

    /**
     * @param Commande $data
     */
    public function persist($data)
    {
        $lignCmdes = $data->getLigneDeCommandes();
        foreach ($lignCmdes as $lignCmde) {
            $QteCmde=$lignCmde->getQuantiteCmde();
            $pU=$lignCmde->getProduit()->getPrix();
            $prixTotalLCmde = $QteCmde*$pU;
            $lignCmde->setPrixLCmde($prixTotalLCmde) ;   
           //    dd($prixTotalLCmde);
        }
        // $data->setPrixCommande($lignCmde->setPrixLCmde($prixTotalLCmde));
        
        $data->setClient($this->token->getUser());
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}