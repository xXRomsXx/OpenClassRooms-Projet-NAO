<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Birds
 *
 * @ORM\Table(name="birds")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BirdsRepository")
 */
class Birds
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="race", type="string", length=255)
     */
    private $race;

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Observations", mappedBy="birdRace")
     *
     */
    private $observations;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set race
     *
     * @param string $race
     *
     * @return Birds
     */
    public function setRace($race)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     *
     * @return string
     */
    public function getRace()
    {
        return $this->race;
    }


    /**
     * @return Collection|Observations[]
     */
    public function getObservations()
    {
        return $this->observations;
    }
}

