<?php

namespace Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="bettypes")
 * @property int $id
 * @property string $name
 * @property Ligues $ligue
 * @property ArrayCollection $matches
 */
class Bettypes {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Ligues", inversedBy="bettypes")
     * @ORM\JoinColumn(name="fk_ligues", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $ligue;

    /**
     * @ORM\OneToMany(targetEntity="Matches", mappedBy="bettype")
     */
    private $matches;

    public function __construct(Ligues $ligue) {
        $this->ligue = $ligue;
        $this->matches = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getLigue() {
        return $this->ligue;
    }

    public function getMatches() {
        return $this->matches;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setLigue(Ligues $ligue) {
        $this->ligue = $ligue;
        return $this;
    }

    public function populate($data = array()) {
        $this->setId(isset($data['id']) ? intval($data['id']) : null);
        $this->setName(isset($data['name']) ? $data['name'] : null);
        return $this;
    }

    public function populateObj(\stdClass $data) {
        $this->setId(isset($data->id) ? intval($data->id) : null);
        $this->setName(isset($data->name) ? $data->name : null);
        return $this;
    }


    public function addMatch(\Database\Entity\Matches $matches)
    {
        $this->matches[] = $matches;
        return $this;
    }

    public function removeMatch(\Database\Entity\Matches $matches)
    {
        $this->matches->removeElement($matches);
    }
}