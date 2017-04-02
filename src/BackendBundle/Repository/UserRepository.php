<?php
namespace BackendBundle\Repository;
class UserRepository extends \Doctrine\ORM\EntityRepository{
    public function getFollowingUsers($user){
        $em=$this->getEntityManager();
        $following_repo = $em->getRepository('BackendBundle:Following');
        $following = $following_repo->findBy(array('user'=>$user));
        $following_array ;
    }

}