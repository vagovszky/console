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
        $import = new \Import\Chance\Importer('https://www.chance.cz/kurzove-sazky/nabidka/xml?obdobi=2&vypisovat=1&pozadavek=vypis');
        var_dump($import->getBettypesByLeagueId(18171890));
        return "Import procedure \n";
    }
}