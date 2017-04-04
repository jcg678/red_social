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

        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                $file=$form['image']->getData();
                if(!empty($file)){
                    $ext = $file->guessExtension();
                    if($ext =='jpg'||$ext == 'jpeg' || $ext == 'png' || $ext == 'gif'){
                        $file_name = $user->getId().time().".".$ext;
                        $file->move("uploads/messages/images", $file_name);
                        $private_message->setImage($file_name);
                    }else{
                        $private_message->setImage(null);
                    }
                }else{
                    $private_message->setImage(null);
                }


                $doc=$form['file']->getData();
                if(!empty($doc)){
                    $ext = $doc->guessExtension();
                    if($ext =='doc'||$ext == 'txt' || $ext == 'pdf' || $ext == 'docx'){
                        $file_name = $user->getId().time().".".$ext;
                        $doc->move("uploads/message/documents", $file_name);
                        $private_message->setFile($file_name);
                    }else{
                        $private_message->setFile(null);
                    }
                }else{
                    $private_message->setFile(null);
                }
                $private_message->setEmitter($user);
                $private_message->setCreatedAt(new \DateTime("now"));
                $private_message->setReaded(0);
                $em->persist($private_message);
                $flush =$em->flush();
                if($flush){
                    $status= "El mensaje no se ha enviado" ;
                }else{
                    $status = "El mensaje se ha enviado correactamente";
                }

            }else{
                $status = "El mensaje no se ha enviado";
            }
            $this->session->getFlashBag()->add("status",$status);
            return $this->redirectToRoute("private_message_index");
        }

        return $this->render('AppBundle:PrivateMessage:index.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    public function sendedAction(Request $request){
        $private_messages = $this->getPrivateMessages($request,"sended");
        return $this->render('AppBundle:privateMessage:sended.html.twig',array(
            'pagination'=>$private_messages
        ));
    }

    public function getPrivateMessages(Request $request,$type = null){
    $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user_id=$user->getId();
        if( $type =="sended" || $type == null){
            $dql = "SELECT p FROM BackendBundle:PrivateMessage p WHERE"
                ." p.emitter = $user_id ORDER BY p.id DESC";

        }else{
            $dql = "SELECT p FROM BackendBundle:PrivateMessage p WHERE"
            ." p.receiver = $user_id ORDER BY p.id DESC";
        }
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            5
        );
        return $pagination;
    }

}