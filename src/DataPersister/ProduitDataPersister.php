<?php
// src/DataPersister/DataPersister.php

namespace App\DataPersister;

// use App\Entity\User;
// use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Menu;
use App\Entity\Boisson;
// use App\Entity\Commande;
use App\Entity\Produit;
use App\Services\PrixMenu;
use App\Entity\LigneDeCommande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Services\FileUploader;
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
        PrixMenu $prixMenu,
        // FileUploader $file
    ) {
        $this->entityManager = $entityManager;
        $this->prixMenu = $prixMenu;
        $this->token = $token->getToken();
        // $this->file = $file;
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
        $link = $data->getImageFile();
        $data->setImage(file_get_contents($link));
        // dd(file_get_contents($link));

        if ($data instanceof Menu) {
            $data->setPrix($this->prixMenu->getPrix($data));
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