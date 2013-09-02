<?php

namespace Application\Options;

use Zend\Stdlib\AbstractOptions;

class SimpleTipOptions extends AbstractOptions implements SimpleTipOptionsInterface {
    
    protected $limit = 400;
    
    protected $profit = 5;
    
    protected $course = 1.3;
    
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
    
}
