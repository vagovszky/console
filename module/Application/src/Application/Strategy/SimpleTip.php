<?php

namespace Application\Strategy;

use Better\Chance\BetInterface;
use Doctrine\ORM\EntityManager;
use Zend\Console\Adapter\AdapterInterface as Console;
use Database\Entity\Tips;
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

    private function findLastTip() {
        $query = $this->em->createQuery('SELECT t FROM Database\Entity\Tips t ORDER BY t.datetime_created DESC');
        $result = $query->setMaxResults(1)->getOneOrNullResult();
        if (empty($result)) {
            return false;
        } else {
            return $result;
        }
    }

    private function findNewOdd() {
        $course = $this->getOptions()->getCourse();
        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array($course, 0, 3));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 1. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array($course, 0, 6));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 2. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array($course, 0.01, 6));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 3. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array($course, 0.02, 6));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 4. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array($course, 0.03, 6));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 5. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;
        
        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array($course, 0.02, 8));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 6. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array($course, 0.03, 8));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 7. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;
        
        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array($course, 0, 10));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 8. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;
        
        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array($course, 0.05, 12));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 9. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        return false;
    }

    public function calculateBet($profit) {
        $course = $this->getOptions()->getCourse();
        return ceil($profit / ($course - 1));
    }

    private function makeNewBet($odd_id, $bet) {
        $limit = $this->getOptions()->getLimit();
        if($bet >= $limit){
            $this->console->write('Bet is heigher than limit ( bet - '.$bet.', limit - '.$limit.' ) !!!' . PHP_EOL);
            $result = false;
        }else{
            $odd = $this->em->getRepository('Database\Entity\Odds')->find($odd_id);
            $match_time = $odd ? $odd->getMatch()->getDatetime()->format('d.m.Y H:i') : "unknown";
            $this->console->write('Creating new bet with odd_id - ' . $odd_id . ' and bet is ' . $bet . ',-Kc Match starts @ [ '.$match_time.' ]' . PHP_EOL);
            $result = $this->chance_better->bet($odd_id, $bet);        
            if ($result) {
                $this->console->write('Bet created successfully...' . PHP_EOL);
                $result = $this->saveNewBet($odd_id, $bet);
            } else {
                $this->console->write('Bet creation failed, not saving !!!' . PHP_EOL);
            }
        }
        return $result;
    }

    private function saveNewBet($odd_id, $bet) {
        try {
            $odd = $this->em->getRepository('Database\Entity\Odds')->find($odd_id);
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
        $odd_id = $this->findNewOdd();
        if ($last_tip) {
            $last_result = $last_tip->getOdd()->getResult();
            $last_bet = $last_tip->getBet();
            switch ($last_result) {
                case "vyhra":
                    $this->console->write('Last result was vyhra, bet was ' . $last_bet . ',-Kc' . PHP_EOL);
                    $new_bet = $this->calculateBet($profit);
                    $result = $this->makeNewBet($odd_id, $new_bet);
                    break;
                case "prohra":
                    $this->console->write('Last result was prohra, bet was ' . $last_bet . ',-Kc' . PHP_EOL);
                    $profit = $last_bet + $profit;
                    $new_bet = $this->calculateBet($profit);
                    $result = $this->makeNewBet($odd_id, $new_bet);
                    break;
                case "zruseno":
                    $this->console->write('Last result was zruseno, bet was ' . $last_bet . ',-Kc' . PHP_EOL);
                    $new_bet = $this->calculateBet($profit);
                    $result = $this->makeNewBet($odd_id, $new_bet);
                    break;
                default:
                    $this->console->write('Last result was not been finished yet - cancel.' . PHP_EOL);
                    $result = true;
                    break;
            }
        } else {
            $this->console->write('No tips created yet.' . PHP_EOL);
            $new_bet = $this->calculateBet($profit);
            $result = $this->makeNewBet($odd_id, $new_bet);
        }
        return $result;
    }
}

