<?php
// src/DataPersister/DataPersister.php

namespace App\DataPersister;

// use App\Entity\User;
// use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Menu;
use App\Entity\Produit;
// use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Services\PrixMenu;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 *
 */
class ProduitDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private ?TokenInterface $token;

    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $token,
        PrixMenu $prixMenu
    ) {
        $this->entityManager = $entityManager;
        $this->prixMenu = $prixMenu;
        $this->token = $token->getToken();
    }
    
    public function supports($data): bool
    {
        return $data instanceof Produit;
    }

    /**
     * @param Produit $data
     */
    public function persist($data)
    {
        if ($data instanceof Menu) {
            $data->setPrix($this->prixMenu->getPrix($data));
            // dd($this->prixMenu->getPrix($data));
            // $prixMenu=$data->prixMenu($data);
            // $data->setPrix($prixMenu);
            // dd($prixMenu);
        }
        
        $data->setUser($this->token->getUser());
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