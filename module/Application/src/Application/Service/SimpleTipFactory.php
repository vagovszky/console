<?php

namespace Application\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Strategy\SimpleTip;

class SimpleTipFactory implements FactoryInterface{
    
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $simpleTip = new SimpleTip($serviceLocator->get('simple_tip_options'));
        
        $simpleTip->setChanceBetter($serviceLocator->get('chance_better'));
        $simpleTip->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));
        $simpleTip->setConsole($serviceLocator->get('console'));
        
        return $simpleTip;
    }
  
}