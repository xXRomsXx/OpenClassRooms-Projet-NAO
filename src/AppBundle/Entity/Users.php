<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsersRepository")
 */
class Users extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="adress", type="string", length=255, nullable=true)
     */
    protected $adress;

    /**
     * @var int
     *
     * @ORM\Column(name="postal_code", type="integer", nullable=true)
     */
    protected $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    protected $status;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Observations", mappedBy="user", cascade={"all"})
    */
    protected $observations;

    /**
     * @var int
     *
     * @ORM\Column(name="observations_published", type="integer", nullable=true)
     */
    protected $observations_published;

    /**
    * @ORM\Column(name="user_picture", type="string", nullable=false)
    *
    * @Assert\File(mimeTypes={ "image/jpeg", "image/png", "image/jpg" })
    */
    protected $picture;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->observations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = array('ROLE_PARTICULIER');
        $this->setStatus("Particulier");
        $this->setPicture(new File('Images/UsersPictures/Avatar.jpeg'));
        $this->setObservationsPublished(0);
    }

    public function setUsernameCanonical($usernameCanonical)
    {
        // On remplace les caractères speciaux
        $usernameCanonical = htmlentities($usernameCanonical, ENT_NOQUOTES, 'utf-8');
        $usernameCanonical = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $usernameCanonical);
        $usernameCanonical = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $usernameCanonical); // pour les ligatures e.g. '&oelig;'
        $usernameCanonical = preg_replace('#&[^;]+;#', '', $usernameCanonical); // supprime les autres caractères

        $this->usernameCanonical = $usernameCanonical;
        return $this;
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
     * Set username
     *
     * @param string $username
     *
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Users
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Users
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set adress
     *
     * @param string $adress
     *
     * @return Users
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set postalCode
     *
     * @param integer $postalCode
     *
     * @return Users
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return int
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Users
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return Users
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Users
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add observation
     *
     * @param \AppBundle\Entity\Observations $observation
     *
     * @return Users
     */
    public function addObservation(\AppBundle\Entity\Observations $observation)
    {
        $this->observations[] = $observation;

        return $this;
    }

    /**
     * Remove observation
     *
     * @param \AppBundle\Entity\Observations $observation
     */
    public function removeObservation(\AppBundle\Entity\Observations $observation)
    {
        $this->observations->removeElement($observation);
    }

    /**
     * Get observations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObservations()
    {
        return $this->observations;
    }


    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Users
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set observationsPublished
     *
     * @param integer $observationsPublished
     *
     * @return Users
     */
    public function setObservationsPublished($observationsPublished)
    {
        $this->observations_published = $observationsPublished;

        return $this;
    }

    /**
     * Get observationsPublished
     *
     * @return integer
     */
    public function getObservationsPublished()
    {
        return $this->observations_published;
    }
}
