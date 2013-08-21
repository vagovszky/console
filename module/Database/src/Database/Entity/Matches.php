<?php

namespace Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Index as Index;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="matches", indexes={@Index(name="number_idx", columns={"number"})})
 * @property int $id
 * @property DateTime $datetime
 * @property string $name
 * @property int $number
 * @property string result
 * @property Bettypes $bettype
 * @property ArrayCollection $odds
 */
class Matches {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datetime;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true, unique=false);
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $result;

    /**
     * @ORM\ManyToOne(targetEntity="Bettypes", inversedBy="matches")
     * @ORM\JoinColumn(name="fk_bettypes", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $bettype;

    /**
     * @ORM\OneToMany(targetEntity="Odds", mappedBy="matches")
     */
    private $odds;

    public function __construct(Bettypes $bettype) {
        $this->bettype = $bettype;
        $this->odds = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getDatetime() {
        return $this->datetime;
    }

    public function getName() {
        return $this->name;
    }

    public function getNumber() {
        return $this->number;
    }

    public function getResult() {
        return $this->result;
    }

    public function getBettype() {
        return $this->bettype;
    }

    public function getOdds() {
        return $this->odds;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setDatetime($datetime) {
        if (isset($datetime)) {
            if (is_string($datetime)) {
                $d = new \DateTime($datetime);
                $this->datetime = $d;
            } else {
                $this->datetime = $datetime;
            }
        } else {
            $this->datetime = null;
        }
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setNumber($number) {
        $this->number = $number;
        return $this;
    }

    public function setResult($result) {
        $this->result = $result;
        return $this;
    }

    public function setBettype(Bettypes $bettype) {
        $this->bettype = $bettype;
        return $this;
    }

    public function populate($data = array()) {
        $this->setId(isset($data['id']) ? intval($data['id']) : null);
        $this->setDatetime(isset($data['date']) ? $data['date'] : null);
        $this->setName(isset($data['name']) ? $data['name'] : null);
        $this->setNumber(isset($data['number']) ? intval($data['number']) : null);
        $this->setResult(isset($data['result']) ? $data['result'] : null);
        return $this;
    }

    public function hydrate(\stdClass $data) {
        $this->setId(isset($data->id) ? intval($data->id) : null);
        $this->setDatetime(isset($data->date) ? $data->date : null);
        $this->setName(isset($data->name) ? $data->name : null);
        $this->setNumber(isset($data->number) ? intval($data->number) : null);
        $this->setResult(isset($data->result) ? $data->result : null);
        return $this;
    }

}

