<?php

namespace Application\Strategy;

use Better\Chance\Bet;
use Doctrine\ORM\EntityManager;
use Zend\Console\Adapter\AdapterInterface as Console;

class SimpleTip{
    
    private $chance_better;
    private $em;
    private $console;
    
    public function setConsole(Console $console){
        $this->console = $console;
    }
    
    public function setChanceBetter(Bet $chance_better){
        $this->chance_better = $chance_better;
        return $this;
    }
    
    public function setEntityManager(EntityManager $em){
        $this->em = $em;
        return $this;
    }
    

    private function checkAllBetsFinished(){
        
    }
    
    private function findNewOdd(){
        
    }
    
    private function calculateBet(){
        
    }
    
    public function run(){
        $this->console->write('Starting simple tip creation...'.PHP_EOL);
    } 
   
}

?>
