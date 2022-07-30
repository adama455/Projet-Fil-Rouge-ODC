<?php
// src/DataPersister/DataPersister.php

namespace App\DataPersister;

use LDAP\Result;
use App\Entity\User;
use App\Entity\Livreur;
use App\Entity\Livraison;
use App\Services\MailerService;
use App\Repository\LivreurRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Namshi\JOSE\JWS;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Json;

/**
 *
 */
class LivraisonDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private ?TokenInterface $token;


    public function __construct(
        EntityManagerInterface $entityManager,
        LivreurRepository $livreurR,
        CommandeRepository $cmdeR,
        TokenStorageInterface $token
    ){
        $this->entityManager = $entityManager;
        $this->livreurR=$livreurR;
        $this->cmdeR=$cmdeR;
        $this->token=$token->getToken();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Livraison;
    }

    /**
     * @param Livraison $data
     */
    public function persist($data, array $context = [])
    {    
        // l'enssemble des commande qui exixte dans la livraison
        $cmdes=$data->getCommandes();
        // dd($cmdes);
        foreach($cmdes as $cmde){
            if ($cmde->getEtat()=="En cours" || $cmde->getEtat()=="terminer"){   //Pour chaque cmde est en cours ou terminer;
                return new JsonResponse(["message"=>"La commande de reference".$cmde->getReference()." est en cours de Livraison!!"],Response::HTTP_BAD_REQUEST);
            }
        }
        // selectionné les livreur dont Etat=1 (disponnible)
        $livreurs=$this->livreurR->findBy([
            "etat" => 1,
            // "isEnable" => true
        ]);
        // Choisir un Livreur aléatoirement
        if (empty($livreurs)) {
            return  new JsonResponse(["message" => "Pas de Livreur disponible!!"],Response::HTTP_BAD_REQUEST);
        }
        $livreurDispoCmde= $livreurs[array_rand($livreurs)];
        $data->setLivreur($livreurDispoCmde);
        foreach ($cmdes as $cmde) {
            $cmde->setEtat("En cours");
            $this->entityManager->persist($cmde);
        }
        $this->entityManager->persist($data);
        $livreurDispoCmde->setEtat(0);
        
        $data->setGestionnaire($this->token->getUser());
        $this->entityManager->persist($livreurDispoCmde);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
    
}