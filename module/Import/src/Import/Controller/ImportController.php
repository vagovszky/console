<?php
namespace Import\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;

class ImportController extends AbstractActionController
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
    
    public function importAction(){
        return "Import procedure \n";
    }
}