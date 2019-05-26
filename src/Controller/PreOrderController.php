<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use App\Form\AddPhoneFormType;
use App\Entity\PreOrder;
use App\Services\TwilioService;

class PreOrderController extends AbstractController
{
    /**
     * @Route("/pre_order", name="pre_order")
     */
    public function index(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if(!$this->getUser()->getPhone()){
            $form = $this->createForm(AddPhoneFormType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $phone_number = $form->get("phone")->getData();
            
                $user = $this->getUser();
                $user->setPhone($phone_number);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }else{
                return $this->render('pre_order/need_phone.twig', [
                    'controller_name' => 'PreOrderController', "add_phone_form" => $form->createView()
                ]);
            }
        }
        $em = $this->getDoctrine()->getManager();
        
        $preorder = new PreOrder();
        $preorder->setUser($this->getUser());
        $preorder->setStatus("waiting");
        $preorder->setDate(new \DateTime());
        
        $baskets = $this->getDoctrine()->getRepository("App\Entity\Basket")->findBy(["user_id" => $this->getUser()]);
        $products = [];
        $products_ids = [];
        foreach ($baskets as $basket){
            if(count($basket->getProductId())){
                $preorder->addProduct($basket->getProductId()[0]);
                if(!isset($products[$basket->getProductId()[0]->getName()])){
                    $products[$basket->getProductId()[0]->getName()] = 1;
                    $products_ids[$basket->getProductId()[0]->getName()] = $basket->getProductId()[0]->getId();
                } else {
                    $products[$basket->getProductId()[0]->getName()]++;
                }
                $basket->removeProductId($basket->getProductId()[0]);
                $em->persist($basket);
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
        
        $em->persist($preorder);
        $em->flush();
        $user_phone = $preorder->getUser()->getPhone();
        $twilio = new TwilioService();
        $twilio->sendMessage($preorder->getId()." li ön siparişiniz oluşturuldu. Diğer güncellemeler SMS ile size bildirilecektir.",
                $user_phone);
        
        $this->addFlash('success', "Ön sipariş oluşturuldu.");
        
        return $this->render('pre_order/index.html.twig', [
            'controller_name' => 'PreOrderController', "products" => $basket_info
        ]);
    } 
}
