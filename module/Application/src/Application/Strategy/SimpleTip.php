<?php

namespace Application\Strategy;

use Better\Chance\Bet;
use Doctrine\ORM\EntityManager;
use Zend\Console\Adapter\AdapterInterface as Console;
use Database\Entity\Tips;

class SimpleTip {

    private $chance_better;
    private $em;
    private $console;

    const COURSE = 1.3;
    const BET = 10;

    public function setConsole(Console $console) {
        $this->console = $console;
    }

    public function setChanceBetter(Bet $chance_better) {
        $this->chance_better = $chance_better;
        return $this;
    }

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
        return $this;
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
        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE, 0, 3));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 1. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE, 0, 6));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 2. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE, 0, 8));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 3. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE, 0.1, 8));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 4. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE, 0.15, 10));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 5. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        $stmt = $this->em->getConnection()->executeQuery('SELECT FindOdd(?, ?, ?)', array(self::COURSE, 0.2, 12));
        $turn = $stmt->fetchColumn(0);
        $this->console->write('Trying find odd 6. - ' . (empty($turn) ? '[ not found ]' : '[ ' . $turn . ' ]') . PHP_EOL);
        if (!empty($turn))
            return $turn;

        return false;
    }

    public function calculateBet($profit) {
        return ceil((100 * $profit) / ((100 * self::COURSE) - 100));
    }

    private function makeNewBet($odd_id, $bet) {
        $this->console->write('Creating new bet with odd_id - ' . $odd_id . ' and bet is ' . $bet . ',-Kc' . PHP_EOL);
        $result = $this->chance_better->bet($odd_id, $bet);        
        $result = true;
        if ($result) {
            $this->console->write('Bet created successfully...' . PHP_EOL);
        } else {
            $this->console->write('Bet creation failed...' . PHP_EOL);
        }
        $result = $result && $this->saveNewBet($odd_id, $bet);
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
            $this->console->write('Bet save to db failed...' . PHP_EOL);
            return false;
        }
    }

    public function run() {
        $this->console->write("Making simple tip... " . PHP_EOL);
        $last_tip = $this->findLastTip();
        $odd_id = $this->findNewOdd();
        if ($last_tip) {
            $last_result = $last_tip->getOdd()->getResult();
            $last_bet = $last_tip->getBet();
            switch ($last_result) {
                case "vyhra":
                    $this->console->write('Last result was vyhra, bet was ' . $last_bet . ',-Kc' . PHP_EOL);
                    $new_bet = self::BET;
                    $result = $this->makeNewBet($odd_id, $new_bet);
                    break;
                case "prohra":
                    $this->console->write('Last result was prohra, bet was ' . $last_bet . ',-Kc' . PHP_EOL);
                    $profit = $last_bet + self::BET;
                    $new_bet = $this->calculateBet($profit);
                    $result = $this->makeNewBet($odd_id, $new_bet);
                    break;
                case "zruseno":
                    $this->console->write('Last result was zruseno, bet was ' . $last_bet . ',-Kc' . PHP_EOL);
                    $new_bet = self::BET;
                    $result = $this->makeNewBet($odd_id, $new_bet);
                    break;
                default:
                    $this->console->write('Last result was not been finished yet - cancel.' . PHP_EOL);
                    $result = false;
                    break;
            }
        } else {
            $this->console->write('No tips created yet.' . PHP_EOL);
            $new_bet = self::BET;
            $result = $this->makeNewBet($odd_id, $new_bet);
        }
        return $result;
    }

}

?>
