<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends Controller
{

    public function indexAction(Request $request)
    {
       $em = $this->getDoctrine()->getManager();
        $user = $this->getUser()->getId();
        $dql= "SELECT  n FROM BackendBundle:Notification n where n.user = $user ORDER BY n.id DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $notifications = $paginator->paginate($query,
            $request->query->getInt('page',1),
            5
        );

        return $this->render('AppBundle:Notification:notification_page.html.twig',array(
           'user'=>$user,
            'pagination'=>$notifications
        ));

    }
}
