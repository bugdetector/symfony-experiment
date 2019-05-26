<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Basket;
use App\Form\AddToBasketFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddToBasketController extends AbstractController
{
    /**
     * @Route("/addtobasket/{product_id}", name="add_to_basket")
     */
    public function index(Request $request, $product_id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $product = $this->getDoctrine()->getRepository("App\Entity\Product")->find($product_id);
        
        $form = $this->createForm(\App\Form\AddToBasketFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $count = $form->get('count')->getData(); //How many pieces are
            
            $i = 0;
            
            //I have done a mistake and because of time constraint I couldn't correct it yet.
            //Because of this error there is one basket for one item.
            for($i = 0; $i < $count; $i++){
                $basket = new Basket();
                $basket->setUserId($this->getUser());
                $basket->setStatus("waiting"); //Dummy not important
                $basket->addProductId($product);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($basket);
                $entityManager->flush();
            }
            $this->addFlash('success', "$count adet {$product->getName()} ürünü sepetinize eklendi.");
        }
        
        return $this->render('add_to_basket/index.html.twig', [
            'controller_name' => 'AddToBasketController', 
            'product' => $product,
            'add_to_basket_form' => $form->createView()
        ]);
    }
}
