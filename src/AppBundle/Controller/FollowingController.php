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
        echo "follow action";
        die();
    }

}