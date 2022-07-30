<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Burger;
use App\Services\PrixMenu;
use App\Repository\FriteRepository;
use App\Repository\BurgerRepository;
use App\Repository\TailleRepository;
use App\Entity\MenuBurger;
use PhpParser\Node\Expr\AssignOp\Mod;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use ProxyManager\Factory\RemoteObject\Adapter\JsonRpc;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuProduitController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $manager,BurgerRepository $burgerR,TailleRepository $tailleR, FriteRepository $friteR,PrixMenu $prixMenu) {
        $content=json_decode($request->getContent());
        if (!isset($content->nom)) {
            return $this->json('Nom Obligatoire',400);
        }elseif (!isset($content->tailles) && !isset($content->frites)) {
            return $this->json('il nous faut aumoins un Frite Ou un boisson',400);
        }elseif(!isset($content->burgers)){
            return $this->json('il nous faut un burger pour un menu',400);
        }
        $menu = new Menu();
        $menu->setNom($content->nom);
        $menu->setPrix($content->prix);
        // $menu->setPrix($content->prix->getPrix($menu));
        // dd($prixMenu->getPrix($menu));
        // $data->setPrix($this->prixMenu->getPrix($data));
        foreach ($content->burgers as $b){
            $burger = $burgerR-> find($b->burger);
            if ($burger) {
                $menu -> addBurger($burger,$b->quatite);
            }
        }
        foreach ($content->tailles as $t){
            $taille = $tailleR-> find($t->taille);
            if ($taille) {
                $menu -> addTaille($taille,$t->quantite);
            }
        }
        foreach ($content->frites as $f){
            $frite = $friteR-> find($f->frite);
            if ($frite) {
                $menu -> addFrite($frite,$f->quantite);
            }
        }
        // dd($content);
        // $bb=$menu->getMenuBurgers();
        // foreach ($bb as $b){
        //     $burger=$b->getBurger();   
        // }
        $manager->persist($menu);
        $manager->flush();
        return $this->json(
            [
                "id" => $menu->getId(),
                "nom" => $menu->getNom(),
                "prix" => $menu->getPrix(),
                
                "burgers" => $menu->getMenuBurgers(),
                "frites" => $menu->getMenuFrites(),
                "tailles" => $menu->getMenuTailles(),
                // "burger" => $menu->getMenuBurgers(),
            ]);//tu peux ajouter le code de la réponse
    }
}
