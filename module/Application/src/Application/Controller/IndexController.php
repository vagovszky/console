<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;

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

}