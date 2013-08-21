<?php
namespace Import\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Zend\Console\Adapter\Virtual;

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
        $console = $this->getConsole();

        if($console instanceof Virtual){
            return "No console support";
        }
       
        $Importer = $this->getServiceLocator()->get('importer');
        $results = $Importer->import();
        $console->write('Added ligues ..... '.$results["ligues_add"].PHP_EOL);
        $console->write('Edited ligues .... '.$results["ligues_edit"].PHP_EOL);
        $console->write('Added bettypes ... '.$results["bettypes_add"].PHP_EOL);
        $console->write('Edited bettypes .. '.$results["bettypes_edit"].PHP_EOL);
        $console->write('Added matches .... '.$results["matches_add"].PHP_EOL);
        $console->write('Edited matches ... '.$results["matches_edit"].PHP_EOL);
        $console->write('Added odds ....... '.$results["odds_add"].PHP_EOL);
        $console->write('Edited odds ...... '.$results["odds_edit"].PHP_EOL.PHP_EOL);
    }

    public function getConsole(){
        return $this->getServiceLocator()->get('console');
    }
}