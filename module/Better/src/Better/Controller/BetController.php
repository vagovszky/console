<?php
namespace Better\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Adapter\Virtual;

class BetController extends AbstractActionController
{
 
    public function betAction(){
        $console = $this->getConsole();
        $request = $this->getRequest();
        if($console instanceof Virtual){
            return "No console support";
        }
        $odd_id = $request->getParam('odd_id');
        $money = $request->getParam('money');
        
        if(!$odd_id || !$money){
            return("Missing parameters \n");
        }
        $better = $this->getServiceLocator()->get('chance_better');
        if($better->bet($odd_id, $money)){
            $console->write('Success...'.PHP_EOL.PHP_EOL);
        }else{
            $console->write('Fail !!!'.PHP_EOL.PHP_EOL);
        }
    }

    public function getConsole(){
        return $this->getServiceLocator()->get('console');
    }
}