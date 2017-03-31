<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $notification = $this->get('app.notification_service');
        $notification->read($user);


        return $this->render('AppBundle:Notification:notification_page.html.twig',array(
           'user'=>$user,
            'pagination'=>$notifications
        ));

    }

    public  function countNotificationsAction(){
        $em = $this->getDoctrine()->getManager();
        $notifications_repo = $em->getRepository('BackendBundle:Notification');
        $notifications=$notifications_repo->findBy(array(
           'user'=>$this->getUser(),
            'readed'=>0
        ));
        return new Response(count($notifications));
    }
}
