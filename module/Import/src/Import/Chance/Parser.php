<?php
namespace Import\Chance;

use \DOMDocument;
use \DOMXPath;
use \stdClass;

class Parser {
    
    private $dom;
    
    public function __construct (){}
    
    public function setSource($source){
        $this->loadDom($source);
        return $this;
    }
    
    protected function loadDom($source){
    	try {
            $doc = new DOMDocument();
            $doc->loadXML(file_get_contents($source));
            $this->dom = new DOMXpath($doc);
        } catch (\Exception $e) {
            throw new \Exception("Cannot get data from xml source!");
        }
    }
    
    protected function getLeagues ()
    {
        return $this->nodeAttrAsArray("//League[@sport!='Favorit dne' and @sport!='Favorit dne'][Bettype[@name='Zápasy']/Oddnames/Oddname='0' and Bettype/Oddnames/Oddname='1' and Bettype/Oddnames/Oddname='2' and Bettype/Oddnames/Oddname='10' and Bettype/Oddnames/Oddname='12' and Bettype/Oddnames/Oddname='02']");
    }
    
    protected function getBettypesByLeagueId ($league_id)
    {
        return $this->nodeAttrAsArray("//League[@sport!='Favorit dne' and @sport!='Favorit dne'][@id='" . $league_id . "']/Bettype[@name='Zápasy' and Oddnames/Oddname='0' and Oddnames/Oddname='1' and Oddnames/Oddname='2' and Oddnames/Oddname='10' and Oddnames/Oddname='12' and Oddnames/Oddname='02']");
    }
    
    protected function getMatchesByBettypeId ($bettype_id)
    {
        return $this->nodeAttrAsArray("//Bettype[@id='" . $bettype_id . "']/Matches/Match");
    }
    
    protected function getOddsByMatchId ($match_id)
    {
        return $this->nodeAttrAsArray("//Match[@id='" . $match_id . "']/Odds/Odd");
    }
    
    protected function dateToDbDate($date){
        $datetime = explode("/",$date);
        $out = $datetime[0].date("Y")." ".$datetime[1];
        return date("Y-m-d H:i:s", strtotime($out));
    }
    
    private function nodeAttrAsArray($query){
        $results = $this->dom->query($query);
        $matches = array();
        foreach ($results as $result) {
            $items = array();
            foreach($result->attributes as $attribute){
                if($attribute->name == "date"){
                    $items[$attribute->name] = $this->dateToDbDate($result->getAttribute($attribute->name));
                }else{
                    $items[$attribute->name] = $result->getAttribute($attribute->name);
                }
            }
            $matches[] = $items;
        }
        return $matches;
    }
    
    public function parse(){
        $leagues = array();
        foreach($this->getLeagues() as $_league){
            $league = (object) $_league;
            $league->bettypes = array();
            foreach($this->getBettypesByLeagueId($league->id) as $_bettype){
                $bettype = (object) $_bettype;
                $bettype->matches = array();
                foreach($this->getMatchesByBettypeId($bettype->id) as $_match){
                    $match = (object) $_match;
                    $match->odds = array();
                    foreach($this->getOddsByMatchId($match->id) as $_odd){
                        $odd = (object) $_odd;
                        $match->odds[$odd->id] = $odd;
                    }
                    $bettype->matches[$match->id] = $match;
                }
                $league->bettypes[$bettype->id] = $bettype;
            }
            $leagues[$league->id] = $league;
        }
        return $leagues;
    }
}
