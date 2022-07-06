<?php

namespace App\Controller;

use App\Entity\Menu;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuProduitController
{
        public function __invoke(Menu $data): Menu
        {
            $this->bookPublishingHandler->handle($data);
    
            return $data;
        }

}
