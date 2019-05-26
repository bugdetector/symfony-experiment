<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\PreOrder;
use App\Services\TwilioService;

class PreOrdersAdminController extends AbstractController
{
    /**
     * @Route("/admin/pre_orders", name="pre_orders_admin")
     */
    public function index(Request $request)
    {        
        if(!$this->getUser() || $this->getUser()->getRoles()[0] != "Administrator"){
            return $this->redirectToRoute('app_login');
        }
        if($request->get("op")){
            $preOrder = $this->getDoctrine()->getRepository("App\Entity\PreOrder")->find($request->get("id"));
            $em = $this->getDoctrine()->getManager();
            
            $message = "";
            switch ($request->get("op")){
                case 'remove':
                    $preOrder->setStatus("rejected");
                    $message = $preOrder->getId()." id li siparişiniz reddedildi.";
                    break;
                case 'approve':
                    $preOrder->setStatus("approved");
                    $message = $preOrder->getId()." id li siparişiniz onaylandı.";
                    break;
            }
            $twilio_service = new TwilioService();
            $twilio_service->sendMessage($message, $preOrder->getUser()->getPhone());
            $em->persist($preOrder);
            $em->flush();
            
            return $this->redirectToRoute("pre_orders_admin");
        }
        
        $preorders = $this->getDoctrine()->getRepository("App\Entity\PreOrder")->findBy(["status" => "waiting"]);
        return $this->render('pre_orders_admin/index.html.twig', [
            'controller_name' => 'PreOrdersAdminController', "preorders" => $preorders
        ]);
    }
}
