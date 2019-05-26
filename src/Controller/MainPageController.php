<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainPageController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     */
    
    
    public function index()
    {
        $products = $this->getDoctrine()->getRepository("App\Entity\Product")->findAll();
        return $this->render('main_page/index.html.twig', [
            'controller_name' => 'MainPageController', 'products' => $products
        ]);
    }
}
