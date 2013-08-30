<?php

namespace Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="ligues")
 * @property int $id
 * @property string $sport
 * @property string $region
 * @property string $name
 * @property ArrayCollection $bettypes
 */
class Ligues {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $sport;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Bettypes", mappedBy="ligue")
     * */
    private $bettypes;

    public function __construct() {
        $this->bettypes = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getSport() {
        return $this->sport;
    }

    public function getRegion() {
        return $this->region;
    }

    public function getName() {
        return $this->name;
    }

    public function getBettypes() {
        return $this->bettypes;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setSport($sport) {
        $this->sport = $sport;
        return $this;
    }

    public function setRegion($region) {
        $this->region = $region;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function populate($data = array()) {
        $this->setId(isset($data['id']) ? intval($data['id']) : null);
        $this->setSport(isset($data['sport']) ? $data['sport'] : null);
        $this->setRegion(isset($data['region']) ? $data['region'] : null);
        $this->setName(isset($data['name']) ? $data['name'] : null);
        return $this;
    }

    public function populateObj(\stdClass $data) {
        $this->setId(isset($data->id) ? intval($data->id) : null);
        $this->setSport(isset($data->sport) ? $data->sport : null);
        $this->setRegion(isset($data->region) ? $data->region : null);
        $this->setName(isset($data->name) ? $data->name : null);
        return $this;
    }


    public function addBettype(\Database\Entity\Bettypes $bettypes)
    {
        $this->bettypes[] = $bettypes;
        return $this;
    }

    public function removeBettype(\Database\Entity\Bettypes $bettypes)
    {
        $this->bettypes->removeElement($bettypes);
    }
}