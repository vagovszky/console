<?php
namespace Import\Chance;

use \DOMDocument;
use \DOMXPath;

class Importer {
    
    private $xml;
    private $dom;
    protected $ommit_results = true;
    
    public function __construct ($source, $ommit_results = true)
    {
        $this->loadXmlDom($source);
        //$this->dom = $dom;
        $this->ommit_results = $ommit_results;
    }
    
    protected function loadXmlDom($source){
    	try {
            $this->xml = file_get_contents($source);
            $doc = new DOMDocument();
            $doc->loadXML($this->xml);
            $this->dom = new DOMXpath($doc);
        } catch (\Exception $e) {
            throw new \Exception("Cannot get data from xml source!");
        }
    }
    
    protected function getLeagues ()
    {
        return $this->nodeAttrAsArray("//League");
    }
    
    public function getBettypesByLeagueId ($league_id)
    {
        return $this->nodeAttrAsArray("//League[@id='" . $league_id . "']/Bettype[Oddnames/Oddname=1 | Oddnames/Oddname=0 | Oddnames/Oddname=2 | Oddnames/Oddname=10 | Oddnames/Oddname=12 | Oddnames/Oddname=02]");
    }
    
    protected function getMatchesByBettypeId ($bettype_id)
    {
        return $this->nodeAttrAsArray("//Bettype[@id='" . $bettype_id . "']/Matches/Match");
    }
    
    protected function getOddsByMatchId ($match_id)
    {
        return $this->nodeAttrAsArray("//Match[@id='" . $match_id . "']/Odds/Odd");
    }
    
    protected function getMatches(){
        return $this->nodeAttrAsArray("//Match");
    }
    
    protected function dateToDbDate($date){
        $datetime = explode("/",$date);
        $out = $datetime[0].date("Y")." ".$datetime[1];
        return date("Y-m-d H:i:s", strtotime($out));
    }
    
    //abstract public function import();
    
        
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
            if(isset($items["result"]) && $this->ommit_results){
                unset($items["result"]);
            }
            $matches[] = $items;
        }
        return $matches;
    }
}
