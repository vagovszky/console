<?php

namespace Application\Strategy;

use Better\Chance\Bet;
use Doctrine\ORM\EntityManager;
use Zend\Console\Adapter\AdapterInterface as Console;

class SimpleTip{
    
    private $chance_better;
    private $em;
    private $console;
    
    const COURSE = 1.3;
    
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
    
    private function findLastTip(){
        $query = $this->em->createQuery('SELECT t FROM Database\Entity\Tips t ORDER BY t.datetime_created DESC');
        $result = $query->setMaxResults(1)->getOneOrNullResult();
        if(empty($result)){
            return false;
        }else{
            return $result;
        }
    }
    
    private function findNewOdd(){
        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE,0,3));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 1. - '.(empty($turn)?'[ not found ]':'[ '.$turn.' ]').PHP_EOL);
        if(!empty($turn)) return $turn;
        
        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE,0,6));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 2. - '.(empty($turn)?'[ not found ]':'[ '.$turn.' ]').PHP_EOL);
        if(!empty($turn)) return $turn;
        
        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE,0,8));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 3. - '.(empty($turn)?'[ not found ]':'[ '.$turn.' ]').PHP_EOL);
        if(!empty($turn)) return $turn;
        
        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE,0.1,8));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 4. - '.(empty($turn)?'[ not found ]':'[ '.$turn.' ]').PHP_EOL);
        if(!empty($turn)) return $turn;
        
        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE,0.15,10));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 5. - '.(empty($turn)?'[ not found ]':'[ '.$turn.' ]').PHP_EOL);
        if(!empty($turn)) return $turn;
        
        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE,0.2,12));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 6. - '.(empty($turn)?'[ not found ]':'[ '.$turn.' ]').PHP_EOL);
        if(!empty($turn)) return $turn;
        
        return false;
    }
    
    private function calculateBet(){
        
    }
    
    public function run(){
        $this->console->write("Making simple tip... ".PHP_EOL);
    } 
   
}

?>
