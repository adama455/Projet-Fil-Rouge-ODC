<?php

namespace App\Controller;

use LDAP\Result;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    public function __invoke(Request $request, UserRepository  $repo,EntityManagerInterface $manager)
    {
        $token=$request->get('token');
        $user=$repo->findOneBy(["token" => $token]);
        if (!$user){
            return new JsonResponse(['error' => 'Invalide token'], Response::HTTP_BAD_REQUEST);
        }


        if ($user->isIsEnable()){
            return new JsonResponse(['message' => 'Votre Compte a été deja activer'], 400);
        }

        if ($user->getExpireAt() < new \DateTime()){
            return new JsonResponse(['error' => 'Invalid token'],Response::HTTP_BAD_REQUEST);
        }
        $user->setIsEnable(true);
        $manager->flush();
        return new JsonResponse(['message'=> 'Votre Compte a été activer avec succés'],Response::HTTP_OK);
    }   
}
