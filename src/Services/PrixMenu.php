<?php
namespace App\Services;

class  PrixMenu
{

    public function getPrix($data){
        $prixMenu = 0;
        $burgers = $data-> getMenuBurgers();
        $tailleBoissons = $data->getMenuTailles();
        $frites = $data->getMenuFrites();

        foreach($burgers as $burger ){
            $qteB=$burger->getQuatite();
            $puB = $burger->getBurger()->getPrix();
            $prixMenu+=$puB*$qteB;
        }
        foreach($tailleBoissons as $boisson ){
            $puBo= $boisson-> getTaille()->getPrix();
            $qteBo=$boisson->getQuantite();
            $prixMenu+=$puBo*$qteBo;
        }
        foreach($frites as $frite ){
            $puF= $frite->getFrite()->getPrix();
            $qteF= $frite->getQuantite();
            $prixMenu+=$puF*$qteF;
        }
      
        return $prixMenu;
    }
}