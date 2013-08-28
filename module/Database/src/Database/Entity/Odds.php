<?php

namespace Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="odds")
 * @property int $id
 * @property string $name
 * @property float $number
 * @property string result
 * @property Matches $match
 */
class Odds{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $name;
    
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $value;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $result;
    
    /**
     * @ORM\ManyToOne(targetEntity="Matches", inversedBy="odds")
     * @ORM\JoinColumn(name="fk_matches", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $match;
    
    public function __construct(Matches $match){
        $this->match = $match;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getValue(){
        return $this->value;
    }
    
    public function getResult(){
        return $this->result;
    }
    
    public function getMatch(){
        return $this->match;
    }
    
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    
    public function setValue($value){
        $this->value = $value;
        return $this;
    }
    
    public function setResult($result){
        $this->result = $result;
        return $this;
    }
    
    public function setMatch(Matches $match){
        $this->match = $match;
        return $this;
    }
    
    public function populate($data = array()) {
        $this->setId(isset($data['id'])?intval($data['id']):null);
        $this->setName(isset($data['name'])?$data['name']:null);
        $this->setValue(isset($data['value'])?$data['value']:null);
        $this->setResult(isset($data['result'])?$data['result']:null);
        return $this;
    }
    
     public function populateObj(\stdClass $data) {
        $this->setId(isset($data->id)?intval($data->id):null);
        $this->setName(isset($data->name)?$data->name:null);
        $this->setValue(isset($data->value)?$data->value:null);
        $this->setResult(isset($data->result)?$data->result:null);
        return $this;
    }
}