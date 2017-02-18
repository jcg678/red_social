<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use BackendBundle\Entity\User;
use AppBundle\Form\RegisterType;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller{
    private $session;
    public function __construct(){
        $this->session= new Session();
    }
    public function loginAction(Request $request){
        if(is_object($this->getUser())){
           return $this->redirect('home');
        }
        $authentucationUtils=$this->get('security.authentication_utils');
        $error = $authentucationUtils->getLastAuthenticationError();
        $lastUsername = $authentucationUtils->getLastUsername();
        return $this->render('AppBundle:User:login.html.twig',[
            "last_username"=>$lastUsername,
            "error"=>$error
        ]);
    }

    public function registerAction(Request $request){
        if(is_object($this->getUser())){
            return $this->redirect('home');
        }
        $user = new User();
        $form = $this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                //$user_repo=$em->getRepository("BackendBundle:User");
                $query = $em->createQuery('SELECT u FROM BackendBundle:User u where u.email = :email or u.nick = :nick ')
                    ->setParameter('email',$form->get('email')->getData())
                    ->setParameter('nick',$form->get('nick')->getData())
                ;
                $user_isset = $query->getResult();

                if(count($user_isset) == 0){

                    $factory =$this->get("security.encoder_factory");
                    $encoder=$factory->getEncoder($user);
                    $password= $encoder->encodePassword($form->get("password")->getData(),$user->getSalt());
                    $user->setPassword($password);
                    $user->setRole("ROLE_USER");
                    $user->setImage(null);
                    $em->persist($user);
                    $flush = $em->flush();
                    if($flush == null){
                        $status="Te has registrado correctamente";
                        $this->session->getFlashBag()->add("status",$status);
                        return $this->redirect("login");
                    }else{
                        $status="No te has registrado";
                    }
                }else{
                    $status="El usuario ya existe";
                }

            }else{
                $status="No te has registrado";
            }
            $this->session->getFlashBag()->add("status",$status);
        }
        return $this->render('AppBundle:User:register.html.twig',[
            "form"=>$form->createView()
        ]);
    }

    public function nickTestAction(Request $request){
        $nick = $request->get("nick");
        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository("BackendBundle:User");
        $user_isset = $user_repo->findOneBy(array("nick"=>$nick));
        $result = "used";
        if(count($user_isset)>=1 && is_object($user_isset)){
            $result="used";
        }else{
            $result="unused";
        }
        return new Response($result);
    }

    public function editUserAction(Request $request){
        return $this->render('AppBundle:User:edit_user.html.twig'

        );
    }

}