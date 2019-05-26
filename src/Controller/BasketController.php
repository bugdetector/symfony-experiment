<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Basket;
use App\Entity\Product;

class BasketController extends AbstractController
{
    /**
     * @Route("/basket", name="basket")
     */
    public function index(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if($request->get("op") == "remove"){
            $product_id = $request->get("product_id");
            $product = $this->getDoctrine()->getRepository("App\Entity\Product")->find($product_id);
            $baskets_to_remove = $this->getDoctrine()->getRepository("App\Entity\Basket")->findBy(["user_id" => $this->getUser()]);
            $count = 0;
            $em = $this->getDoctrine()->getManager();
            foreach ($baskets_to_remove as $basket){
                if(count($basket->getProductId()) && $basket->getProductId()[0]->getId() == $product_id){
                    $basket->removeProductId($product);
                    $em->persist($basket);
                    $em->flush();
                    $count++;
                }
            }
            $this->addFlash('success', "$count adet {$product->getName()} ürünü sepetinden silindi.");
            return $this->redirectToRoute("basket");
        }
        
        $baskets = $this->getDoctrine()->getRepository("App\Entity\Basket")->findBy(["user_id" => $this->getUser()]);
        $products = [];
        $products_ids = [];
        foreach ($baskets as $basket){
            if(count($basket->getProductId())){
                if(!isset($products[$basket->getProductId()[0]->getName()])){
                    $products[$basket->getProductId()[0]->getName()] = 1;
                    $products_ids[$basket->getProductId()[0]->getName()] = $basket->getProductId()[0]->getId();
                } else {
                    $products[$basket->getProductId()[0]->getName()]++;
                }
            }
        }
        $basket_info = [];
        foreach ($products as $name => $count){
            $info = new \stdClass();
            $info->name = $name;
            $info->count = $count;
            $info->id = $products_ids[$name];
            $basket_info[] = $info;
        }
        return $this->render('basket/index.html.twig', [
            'controller_name' => 'BasketController', 'products' => $basket_info 
        ]);
    }
}
