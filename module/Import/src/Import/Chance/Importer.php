<?php

namespace Import\Chance;

use Database\Entity\Ligues;
use Database\Entity\Bettypes;
use Database\Entity\Matches;
use Database\Entity\Odds;

class Importer
{
    private $entityManager;
    private $parser;
    private $sources = array();
    
    private $cnt_ligues_add = 0; 
    private $cnt_ligues_edit = 0;
    private $cnt_bettypes_add = 0; 
    private $cnt_bettypes_edit = 0;
    private $cnt_matches_add = 0; 
    private $cnt_matches_edit = 0;
    private $cnt_odds_add = 0; 
    private $cnt_odds_edit = 0;
    
    public function __construct (Parser $parser)
    {
        $this->parser = $parser;
    }
    
    public function setSources(array $sources){
        $this->sources = $this->prepareSources($sources);
        return $this;
    }
    
    public function setEntityManager($entityManager){
        $this->entityManager = $entityManager;
        return $this;
    }
    
    public function getEntityManager(){
        return $this->entityManager;
    }
    
    private function prepareSources(array $sources){
        $yesterday = date("d.m.Y", strtotime("-1 day"));   
        $sources['results'] = preg_replace("/_DATE_/", $yesterday, $sources['results']);
        return $sources;
    }

    protected function importSource($source){
        $parser = $this->parser->setSource($source);
        $data = $parser->parse();
        foreach ($data as $_ligue){ // $_ligue contains array with values for Ligues entity
            if(!$ligue = $this->getEntityManager()->find('Database\Entity\Ligues', $_ligue->id)){
                $ligue = new Ligues();  // Create new entity Ligues
                $this->cnt_ligues_add++;
            } else {
                $this->cnt_ligues_edit++;
            }
            $ligue->populateObj($_ligue); // Map $_ligue array into object properties
            $this->getEntityManager()->persist($ligue); // persist entity
            foreach ($_ligue->bettypes as $_bettype){ // $_bettybe contains array with values for Bettype entity
                if(!$bettype = $this->getEntityManager()->find('Database\Entity\Bettypes', $_bettype->id)){
                    $bettype = new Bettypes($ligue); // Create new Bettype entity and set parent Ligue entity
                    $this->cnt_bettypes_add++;
                } else {
                    $this->cnt_bettypes_edit++;
                }
                $bettype->populateObj($_bettype); // map $_bettype array into object properties
                $this->getEntityManager()->persist($bettype); // persist
                foreach($_bettype->matches as $_match){ // --||--
                    if(!$match = $this->getEntityManager()->find('Database\Entity\Matches', $_match->id)){
                        $match = new Matches($bettype); // --||--
                        $this->cnt_matches_add++;
                    }else{ 
                        $this->cnt_matches_edit++;
                    }
                    $match->populateObj($_match); // --||--
                    $this->getEntityManager()->persist($match); // --||--
                    foreach ($_match->odds as $_odd){ // --||--
                        if(!$odd = $this->getEntityManager()->find('Database\Entity\Odds', $_odd->id)){
                            $odd = new Odds($match); // --||--
                            $this->cnt_odds_add++;
                        }else{ 
                            $this->cnt_odds_edit++;
                        }
                        $odd->populateObj($_odd); // --||--
                        $this->getEntityManager()->persist($odd); // --||--
                    }
                }
            }
        }
        $this->getEntityManager()->flush(); // flush data
    }
    
    public function import(){
        foreach($this->sources as $source){
            $this->importSource($source);
        }
        return array(
            "ligues_add" => $this->cnt_ligues_add, "ligues_edit" => $this->cnt_ligues_edit,
            "bettypes_add" => $this->cnt_bettypes_add, "bettypes_edit" => $this->cnt_bettypes_edit,
            "matches_add" => $this->cnt_matches_add, "matches_edit" => $this->cnt_matches_edit,
            "odds_add" => $this->cnt_odds_add, "odds_edit" => $this->cnt_odds_edit
        );
    }
}
?>
