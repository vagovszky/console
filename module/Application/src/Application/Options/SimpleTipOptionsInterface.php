<?php

namespace Application\Options;

interface SimpleTipOptionsInterface{
    
    public function setLimit($limit);
    
    public function getLimit();
    
    public function setProfit($profit);
    
    public function getProfit();
    
    public function setCourse($course);
    
    public function getCourse();
    
    public function getStrategyMap();
    
    public function setStrategyMap($strategyMap);
    
}
