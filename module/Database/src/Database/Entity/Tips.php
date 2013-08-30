<?php

namespace Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Index as Index;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="tips")
 */
class Tips {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $bet;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datetime_created;

    /**
     * @ORM\OneToOne(targetEntity="Database\Entity\Odds")
     * @ORM\JoinColumn(name="fk_odds", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $odd;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set bet
     *
     * @param float $bet
     * @return Tips
     */
    public function setBet($bet) {
        $this->bet = $bet;

        return $this;
    }

    /**
     * Get bet
     *
     * @return float 
     */
    public function getBet() {
        return $this->bet;
    }

    /**
     * Set odd
     *
     * @param \Database\Entity\Odds $odd
     * @return Tips
     */
    public function setOdd(\Database\Entity\Odds $odd) {
        $this->odd = $odd;

        return $this;
    }

    /**
     * Get odd
     *
     * @return \Database\Entity\Odds 
     */
    public function getOdd() {
        return $this->odd;
    }

    public function setDatetimeCreated($datetime_created) {
        if (isset($datetime_created)) {
            if (is_string($datetime_created)) {
                $d = new \DateTime($datetime_created);
                $this->datetime_created = $d;
            } else {
                $this->datetime_created = $datetime_created;
            }
        } else {
            $this->datetime_created = null;
        }
        return $this;
    }

    public function getDatetimeCreated() {
        return $this->datetime_created;
    }

}