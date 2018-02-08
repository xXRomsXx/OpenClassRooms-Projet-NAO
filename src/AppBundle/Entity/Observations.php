<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Observations
 *
 * @ORM\Table(name="observations")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObservationsRepository")
 */
class Observations
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
     * @ORM\Column(name="author", type="string", length=255)
     *
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="bird_name", type="string", length=255)
     *
     * @Assert\NotBlank(message = "Le nom de l'oiseau est obligatoire")
     *
     */
    private $birdName;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Birds", inversedBy="Observations")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotBlank(message = "Le nom de l'espèce est obligatoire")
     *
     */
    private $birdRace;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="decimal", precision=8, scale=6)
     *
     * @Assert\NotBlank(message = "La latitude est obligatoire")
     * @Assert\Regex(
     *      pattern ="/^\-?[0-9]{1,2}\.[0-9]{6}$/",
     *      match=true,
     *      message="Veuillez entrer une latitude valide. Un ou deux chiffres, suivis d'un point, suivit de 6 chiffres (ex: 2.542536 ou 45.124578)"
     * )
     *
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="decimal", precision=8, scale=6)
     *
     * @Assert\NotBlank(message = "La longitude est obligatoire")
     * @Assert\Regex(
     *      pattern ="/^\-?[0-9]{1,2}\.[0-9]{6}$/",
     *      match=true,
     *      message="Veuillez entrer une longitude valide. Un ou deux chiffres, suivis d'un point, suivit de 6 chiffres (ex: 2.542536 ou 45.124578)"
     * )
     *
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string")
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_at", type="datetime")
     */
    private $publishedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="reported", type="integer")
     */
    private $reported;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Pictures", mappedBy="observation", cascade={"all"})
    */

    private $pictures;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Users", inversedBy="observations", cascade={"all"})
    */

    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pictures = new \Doctrine\Common\Collections\ArrayCollection();
        $this->state = "En attente";
        $this->publishedAt = new \DateTime();
        $this->reported = 0;
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
     * Set author
     *
     * @param string $author
     *
     * @return Observations
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set birdName
     *
     * @param string $birdName
     *
     * @return Observations
     */
    public function setBirdName($birdName)
    {
        $this->birdName = $birdName;

        return $this;
    }

    /**
     * Get birdName
     *
     * @return string
     */
    public function getBirdName()
    {
        return $this->birdName;
    }

    /**
     * Set birdRace
     *
     * @param string $birdRace
     *
     * @return Observations
     */
    public function setBirdRace($birdRace)
    {
        $this->birdRace = $birdRace;

        return $this;
    }

    /**
     * Get birdRace
     *
     * @return string
     */
    public function getBirdRace()
    {
        return $this->birdRace;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Observations
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Observations
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Observations
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set published
     *
     * @param integer $published
     *
     * @return Observations
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return int
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return Observations
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set reported
     *
     * @param boolean $reported
     *
     * @return Observations
     */
    public function setReported($reported)
    {
        $this->reported = $reported;

        return $this;
    }

    /**
     * Get reported
     *
     * @return bool
     */
    public function getReported()
    {
        return $this->reported;
    }

    /**
     * Add picture
     *
     * @param \AppBundle\Entity\Pictures $picture
     *
     * @return Observations
     */
    public function addPicture(\AppBundle\Entity\Pictures $picture)
    {
        $this->pictures[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \AppBundle\Entity\Pictures $picture
     */
    public function removePicture(\AppBundle\Entity\Pictures $picture)
    {
        $this->pictures->removeElement($picture);
    }

    /**
     * Get pictures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\Users $user
     *
     * @return Observations
     */
    public function setUser(\AppBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Observations
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
}
