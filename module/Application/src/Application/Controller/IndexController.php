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
    
    public function getConsole(){
        return $this->getServiceLocator()->get('console');
    }
    
   
    public function defaultAction(){
        $simpleTip = $this->getServiceLocator()->get('simpleTip');
        $result = $simpleTip->run();
        $console = $this->getConsole();
        if($console instanceof Virtual){
            return "No console support !!!";
        }
        if($result){
            $console->write('Finished successfully ... [ '.date('d.m.Y H:i:s').' ]'.PHP_EOL);
        }else{
            $console->write('Finished with errors !!! ... [ '.date('d.m.Y H:i:s').' ]'.PHP_EOL);
        }
    }
}