<?php
namespace Import\Chance;

class ResultsImporter extends Importer {

    private $entityManager;

    public function __construct($dom) {
        parent::__construct($dom, false);
    }

    public function setEntityManager($entityManager) {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function getEntityManager() {
        return $this->entityManager;
    }

    public function import() {
        $udtd_matches_results = 0;
        $udtd_odds_results = 0;
        foreach ($this->getMatches() as $_match) {
            if($match = $this->getEntityManager()->find('Application\Entity\Matches', $_match["id"])){
                $match->setResult($_match["result"]);
                $this->getEntityManager()->persist($match);
                $udtd_matches_results++;
                foreach ($this->getOddsByMatchId($_match["id"]) as $_odd) {
                    if($odd = $this->getEntityManager()->find('Application\Entity\Odds', $_odd["id"])){
                        $odd->setResult($_odd["result"]);
                        $this->getEntityManager()->persist($odd);
                        $udtd_odds_results++;
                    }
                }
            }
        }
        $this->getEntityManager()->flush();
        return array(
            'updated_matches_results' => $udtd_matches_results,
            'updated_odds_results' => $udtd_odds_results
            );
    }

}
