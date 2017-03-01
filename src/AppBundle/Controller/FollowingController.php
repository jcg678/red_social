<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use BackendBundle\Entity\User;
use BackendBundle\Entity\Following;
use AppBundle\Form\RegisterType;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\UserType;

class FollowingController extends Controller{
    private $session;
    public function __construct(){
        $this->session= new Session();
    }

    public function followAction(Request $request){
        $user = $this->getUser();
        $followed_id=$request->get('followed');
        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository('BackendBundle:User');
        $followed = $user_repo->find($followed_id);
        $following = new Following();
        $following->setUser($user);
        $following->setFollowed($followed);
        $em->persist($following);
        $flush = $em->flush();

        if($flush == null){
            $status= "Ahora estás siguiendo a este usuario!!";
        }else{
            $status= "No se ha podido seguir a este usuario";
        }
        return new Response($status);
    }

}