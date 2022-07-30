<?php

namespace App\Services;

class MontantCmde
{

    public function montantCommande($data)
    {
        $total = 0;
        $ligneCmde=$data->getLigneDeCommandes();
        foreach ($ligneCmde as $burger) {
            $prix = $burger->getProduit()->getPrix() * $burger->getQuantiteCmde();
            $burger->setPrixLCmde($prix);
            $total += $prix;
            // dd($total);
        }
        
        // foreach ($ligneCmde as $taille) {
        //     $prix = $taille->getTailleBoisson()->getTaille()->getPrix() * $taille->getQuantiteCmde();
        //     $taille->setPrixLCmde($prix);
        //     $total += $prix;
        // }
            
            // foreach ($ligneCmde as $frite) {
            //     $prix = $frite->getProduit()->getPrix() * $frite->getQuantiteCmde();
            //     $frite->setPrixLCmde($prix);
            //     $total += $prix;
            // }
        // foreach ($ligneCmde as $menu) {
        //     $prix = $menu->getMenu()->getPrix() * $menu->getQuantiteCmde();
        //     $menu->setPrixLCmde($prix);
        //     $total += $prix;
        // }
        // $total+=$data->getZone()->getPrix();
        return $total;
    }
}