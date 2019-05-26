<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UsersRoles;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $user->setUsername(
                    $this->find_username_from_email($user->getEmail(), $entityManager)
                    );
            $user->setStatus("active");
            $user->setCreateDate(date("Y-m-d H:i:s"));
            $user->setLastAccess(date("Y-m-d H:i:s"));

            $entityManager->persist($user);
            $entityManager->flush();
            
            $role = $entityManager->getRepository("App\Entity\Role")->findOneBy(['name'=> 'User']);
            
            $user_role = new UsersRoles();
            $user_role->setUserId($user);
            $user_role->setRoleId($role);
            $user->addRole($user_role);

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
    private function find_username_from_email($mail, $entityManager){
        $mail_info = explode("@",$mail);
        $username = $mail_info[0];
        $temp_username = $username;
        while (true){
            $result = $entityManager->getRepository("App\Entity\User")->findBy(["username" => $temp_username]);
            if(!$result){
                return $temp_username;
            }
            $temp_username = $username. random_int(1, 100);
        }
    }
}
