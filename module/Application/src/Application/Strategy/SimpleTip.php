<?php

namespace Application\Strategy;

use Better\Chance\BetInterface;
use Doctrine\ORM\EntityManager;
use Zend\Console\Adapter\AdapterInterface as Console;
use BetDatabase\Entity\Tips;
use BetDatabase\Entity\Odds;
use Application\Options\SimpleTipOptions;

class SimpleTip {

    private $chance_better;
    private $em;
    private $console;
    private $options;

    public function __construct(SimpleTipOptions $options) {
        $this->options = $options;
    }

    public function setConsole(Console $console) {
        $this->console = $console;
    }

    public function setChanceBetter(BetInterface $chance_better) {
        $this->chance_better = $chance_better;
        return $this;
    }

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
        return $this;
    }
    
    public function getOptions(){
        return $this->options;
    }

    // -------------------------------------------------------------------------
    
    private function findLastTip() {
        $query = $this->em->createQuery('SELECT t FROM BetDatabase\Entity\Tips t ORDER BY t.datetime_created DESC');
        $result = $query->setMaxResults(1)->getOneOrNullResult();
        if (empty($result)) {
            return false;
        } else {
            return $result;
        }
    }

    private function findNewOdd() {
        $course = $this->getOptions()->getCourse();
        $strategyMap = $this->getOptions()->getStrategyMap();
        foreach($strategyMap as $i => $option){
            $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array($course, $option[0], $option[1]));
            $turn = $stmt->fetchColumn(0);
            $this->console->write('Trying find odd '.$i.'. ( delta course '.$option[0].', hours +'.$option[1].' ) - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
            if (!empty($turn)) return $this->em->getRepository('BetDatabase\Entity\Odds')->find($turn);
        }
        return NULL;
    }

    public function calculateBet($profit, Odds $odd) {
        $realCourse = floatval($odd->getValue());
        if(!empty($odd)){
            return ceil($profit / ($realCourse - 1));
        }else{
            return NULL;
        }
    }

    private function makeNewBet(Odds $odd, $bet) {
        if(empty($odd) || empty($bet)){
            $this->console->write('Odd not found or bad bet!!!' . PHP_EOL);
            return false;
        }
        $limit = $this->getOptions()->getLimit();
        if($bet >= $limit){
            $this->console->write('Bet is heigher than limit ( bet - '.$bet.', limit - '.$limit.' ) !!!' . PHP_EOL);
            $result = false;
        }else{
            $match_time = $odd ? $odd->getMatch()->getDatetime()->format('d.m.Y H:i') : "unknown";
            $this->console->write('Creating new bet with odd_id - ' . $odd->getId() . ' and bet is ' . $bet . ',-Kc Match starts @ [ '.$match_time.' ]' . PHP_EOL);
            $result = $this->chance_better->bet($odd->getId(), $bet);        
            if ($result) {
                $this->console->write('Bet created successfully...' . PHP_EOL);
                $result = $this->saveNewBet($odd, $bet);
            } else {
                $this->console->write('Bet creation failed, not saving !!!' . PHP_EOL);
            }
        }
        return $result;
    }

    private function saveNewBet(Odds $odd, $bet) {
        try {
            $tips = new Tips();
            $tips->setBet($bet);
            $tips->setDatetimeCreated(new \DateTime());
            $tips->setOdd($odd);
            $this->em->persist($tips);
            $this->em->flush();
            $this->console->write('Bet save to db successfully...' . PHP_EOL);
            return true;
        } catch (\Exception $e) {
            $this->console->write('Bet save to db failed !!!' . PHP_EOL);
            return false;
        }
    }

    public function run() {
        $profit = $this->getOptions()->getProfit();
        $this->console->write('Making simple tip... [ '.date('d.m.Y H:i:s').' ]' . PHP_EOL);
        $last_tip = $this->findLastTip();
        $odd = $this->findNewOdd();
        if ($last_tip && (!empty($odd))) {
            $last_result = $last_tip->getOdd()->getResult();
            $last_bet = $last_tip->getBet();
            switch ($last_result) {
                case "vyhra":
                    $this->console->write('Last result was vyhra, bet was ' . $last_bet . ',-Kc' . PHP_EOL);
                    $new_bet = $this->calculateBet($profit, $odd);
                    $result = $this->makeNewBet($odd, $new_bet);
                    break;
                case "prohra":
                    $this->console->write('Last result was prohra, bet was ' . $last_bet . ',-Kc' . PHP_EOL);
                    $profit = $last_bet + $profit;
                    $new_bet = $this->calculateBet($profit, $odd);
                    $result = $this->makeNewBet($odd, $new_bet);
                    break;
                case "zruseno":
                    $this->console->write('Last result was zruseno, bet was ' . $last_bet . ',-Kc' . PHP_EOL);
                    $new_bet = $this->calculateBet($profit, $odd);
                    $result = $this->makeNewBet($odd, $new_bet);
                    break;
                default:
                    $this->console->write('Last result was not been finished yet - cancel.' . PHP_EOL);
                    $result = true;
                    break;
            }
        } else {
            $this->console->write('No tips created yet or no bet available.' . PHP_EOL);
            $new_bet = $this->calculateBet($profit, $odd);
            $result = $this->makeNewBet($odd, $new_bet);
        }
        return $result;
    }
}

