<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractActionController
{
 
    protected $em;
    
    public function setEntityManager(EntityManager $em){
        $this->em = $em;
    }
    
    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }
    
    public function defaultAction(){
        //$stmt = $this->getEntityManager()->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(1.5,0.5,6));
        //var_dump($stmt->fetchColumn(0));
        return "This is default console action\n";
    }
}