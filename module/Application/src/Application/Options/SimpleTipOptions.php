<?php

namespace Application\Options;

use Zend\Stdlib\AbstractOptions;

class SimpleTipOptions extends AbstractOptions implements SimpleTipOptionsInterface {
    
    protected $limit = 400;
    
    protected $profit = 5;
    
    protected $course = 1.3;
    
    // turn 1 - corse difference 0, hours +3
    protected $strategyMap = array(
        1 => array(0, 3), 
        2 => array(0,6),
        3 => array(0,8),
        4 => array(0.2,10),
    );
    
    public function setLimit($limit){
        $this->limit = $limit;
        return $this;
    }
    
    public function getLimit(){
        return $this->limit;
    }
    
    public function setProfit($profit){
        $this->profit = $profit;
        return $this;
    }
    
    public function getProfit(){
        return $this->profit;
    }
    
    public function setCourse($course){
        $this->course = $course;
        return $this;
    }
    
    public function getCourse(){
        return $this->course;
    }
    
    public function getStrategyMap(){
        return $this->strategyMap;
    }
    
    public function setStrategyMap($strategyMap){
        $this->strategyMap = $strategyMap;
        return $this;
    }
    
}
