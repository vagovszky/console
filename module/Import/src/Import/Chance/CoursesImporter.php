<?php

namespace Import\Chance;

use Application\Entity\Ligues;
use Application\Entity\Bettypes;
use Application\Entity\Matches;
use Application\Entity\Odds;

class CoursesImporter extends Importer
{
    private $entityManager;
    
    public function __construct ($dom)
    {
        parent::__construct($dom, true);
    }
    
    public function setEntityManager($entityManager){
        $this->entityManager = $entityManager;
        return $this;
    }
    
    public function getEntityManager(){
        return $this->entityManager;
    }

    public function import(){
        $cnt_ligues_add = 0; $cnt_ligues_edit = 0;
        $cnt_bettypes_add = 0; $cnt_bettypes_edit = 0;
        $cnt_matches_add = 0; $cnt_matches_edit = 0;
        $cnt_odds_add = 0; $cnt_odds_edit = 0;
        foreach ($this->getLeagues() as $_ligue){ // $_ligue contains array with values for Ligues entity
            if(!$ligue = $this->getEntityManager()->find('Application\Entity\Ligues', $_ligue["id"])){
                $ligue = new Ligues();  // Create new entity Ligues
                $cnt_ligues_add++;
            } else $cnt_ligues_edit++;
            $ligue->populate($_ligue); // Map $_ligue array into object properties
            $this->getEntityManager()->persist($ligue); // persist entity
            foreach ($this->getBettypesByLeagueId($_ligue["id"]) as $_bettype){ // $_bettybe contains array with values for Bettype entity
                if(!$bettype = $this->getEntityManager()->find('Application\Entity\Bettypes', $_bettype["id"])){
                    $bettype = new Bettypes($ligue); // Create new Bettype entity and set parent Ligue entity
                    $cnt_bettypes_add++;
                } else $cnt_bettypes_edit++;
                $bettype->populate($_bettype); // map $_bettype array into object properties
                $this->getEntityManager()->persist($bettype); // persist
                foreach($this->getMatchesByBettypeId($_bettype["id"]) as $_match){ // --||--
                    if(!$match = $this->getEntityManager()->find('Application\Entity\Matches', $_match["id"])){
                        $match = new Matches($bettype); // --||--
                        $cnt_matches_add++;
                    }else $cnt_matches_edit++;
                    $match->populate($_match); // --||--
                    $this->getEntityManager()->persist($match); // --||--
                    foreach ($this->getOddsByMatchId($_match["id"]) as $_odd){ // --||--
                        if(!$odd = $this->getEntityManager()->find('Application\Entity\Odds', $_odd["id"])){
                            $odd = new Odds($match); // --||--
                            $cnt_odds_add++;
                        }else $cnt_odds_edit++;
                        $odd->populate($_odd); // --||--
                        $this->getEntityManager()->persist($odd); // --||--
                    }
                }
            }
        }
        $this->getEntityManager()->flush(); // flush data
        return array(
            "ligues_add" => $cnt_ligues_add, "ligues_edit" => $cnt_ligues_edit,
            "bettypes_add" => $cnt_bettypes_add, "bettypes_edit" => $cnt_bettypes_edit,
            "matches_add" => $cnt_matches_add, "matches_edit" => $cnt_matches_edit,
            "odds_add" => $cnt_odds_add, "odds_edit" => $cnt_odds_edit
        );
    }
}
?>
