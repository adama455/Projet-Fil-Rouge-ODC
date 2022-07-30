<?php
// src/DataPersister/DataPersister.php

namespace App\DataPersister;

// use App\Entity\User;
// use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

use App\Entity\Boisson;
use App\Entity\Commande;
use App\Services\PrixCommande;
use App\Entity\LigneDeCommande;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Services\MontantCmde;
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
        MailerService  $mailerService,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $token,
        MontantCmde $montant
        
    ) {
        $this->entityManager = $entityManager;
        $this->_mailerService = $mailerService;
        $this->token = $token->getToken();
        $this->montant = $montant;
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
        foreach ($data->getLigneDeCommandes() as $ligneDeCommande) {
            $produit = $ligneDeCommande->getProduit();
            if ($produit instanceof Boisson){
                $tailles = $produit->getTailleBoissons();
                foreach ($tailles as $taille ){
                    $stock= $taille->getStock();
                    $taille->setStock($stock-($ligneDeCommande->getQuantiteCmde()));
                    // dd($stock);
                }
            }
        }
        
        $prixCmde = $this->montant->montantCommande($data);
        $data->setMontantCommande($prixCmde * (1-$data->getRemise()));
        // $data->setMontantCommande($data->getMontantCommande());
        
        $data->setClient($this->token->getUser());
        // $this->_mailerService->sendEmail($data);
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