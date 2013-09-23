<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Zend\Console\ColorInterface as Color;

class IndexController extends AbstractActionController {

    protected $em;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    public function getConsole() {
        return $this->getServiceLocator()->get('console');
    }

    public function defaultAction() {
        $simpleTip = $this->getServiceLocator()->get('simpleTip');
        $result = $simpleTip->run();
        $console = $this->getConsole();
        if ($console instanceof Virtual) {
            return "No console support !!!";
        }
        if ($result) {
            $console->write('Finished successfully ... [ ' . date('d.m.Y H:i:s') . ' ]' . PHP_EOL . PHP_EOL);
        } else {
            $console->write('Finished with errors !!! ... [ ' . date('d.m.Y H:i:s') . ' ]' . PHP_EOL . PHP_EOL);
        }
    }

    public function truncateAction() {
        $console = $this->getConsole();
        if ($console instanceof Virtual) {
            return "No console support !!!";
        }
        $connection = $this->getEntityManager()->getConnection();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $connection->query('TRUNCATE TABLE tips');
            $connection->query('TRUNCATE TABLE odds');
            $connection->query('TRUNCATE TABLE matches');
            $connection->query('TRUNCATE TABLE bettypes');
            $connection->query('TRUNCATE TABLE ligues');
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
            $console->write('All tables successfully truncated' . PHP_EOL . PHP_EOL);
        } catch (\Exception $e) {
            $connection->rollback();
            $console->write('Error during truncate tables' . PHP_EOL . PHP_EOL);
        }
    }
    
    public function infoAction(){
        $console = $this->getConsole();
        if ($console instanceof Virtual) {
            return "No console support !!!";
        }
        $query = $this->getEntityManager()->createQuery('SELECT t FROM BetDatabase\Entity\Tips t ORDER BY t.datetime_created DESC');
        $last_tip = $query->setMaxResults(1)->getOneOrNullResult();
        if(!empty($last_tip)){
            $odd = $last_tip->getOdd();
            $match = $odd->getMatch();
            $bettype = $match->getBettype();
            $ligue = $bettype->getLigue();
            $console->write('INFORMATIONS ABOUT LAST TIP:'.PHP_EOL.PHP_EOL, Color::LIGHT_CYAN);
            $console->write('Ligue: ');
            $console->write('name '.$ligue->getName().', region '.$ligue->getRegion().', sport '.$ligue->getSport().PHP_EOL, Color::LIGHT_YELLOW);
            $console->write('Bettype: ');
            $console->write('name '.$bettype->getName().PHP_EOL, Color::LIGHT_YELLOW);
            $console->write('Match: ');
            $result = $odd->getResult(); 
            $console->write('start '.$match->getDatetime()->format('d.m.Y H:i:s').', name: '.$match->getName().', result '.(empty($result)?"N/A":$result).PHP_EOL, Color::LIGHT_YELLOW);
                        $console->write('Odd: ');
            $result = $odd->getResult(); 
            $console->write('tip '.$odd->getName().', course: '.$odd->getValue().', result '.(empty($result)?"N/A":$result).PHP_EOL, Color::LIGHT_YELLOW);
            $console->write('Tip: ');
            $console->write('created '.$last_tip->getDatetimeCreated()->format('d.m.Y H:i:s').', bet '.$last_tip->getBet().',- Kc'.PHP_EOL.PHP_EOL, Color::LIGHT_YELLOW);
            
            
            
        }else{
            $console->write('No tip found' . PHP_EOL . PHP_EOL, Color::LIGHT_RED);
        }
    }

}