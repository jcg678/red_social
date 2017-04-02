<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use BackendBundle\Entity\User;
use AppBundle\Form\RegisterType;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\UserType;
use BackendBundle\Entity\PrivateMessage;
use AppBundle\Form\PrivateMessageType;

class PrivateMessageController extends Controller{
    private $session;
    public function __construct(){
        $this->session= new Session();
    }
    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user=$this->getUser();
        $private_message = new PrivateMessage();
        $form = $this->createForm(PrivateMessageType::class, $private_message,array(
            'empty_data'=>$user
        ));

        return $this->render('AppBundle:PrivateMessage:index.html.twig',array(
            'form'=>$form->createView()
        ));
    }

}