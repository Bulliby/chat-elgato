<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Bulliby\UserBundle\Entity\UserBase;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Bulliby\UserBundle\Repository\UserRepository")
 */
class User extends UserBase
{
    /**
     * @var string
	 * @Assert\NotBlank()
	 * @Assert\Regex(
	 *  pattern="/^[a-zA-Z]+$/",
	 *	match=true,
	 *  message="Your name must contain only letters"
	 * )
	 * @Assert\Length(
	 *  max = 20,
	 *  maxMessage = "Your name cannot be longer than {{ limit }} characters"
	 * )
     * @ORM\Column(name="name", type="string", length=20)
     */
    private $name;

    /**
     * @var string
	 * @Assert\NotBlank
	 * @Assert\Regex(
	 *  pattern="/^[a-zA-Z]+$/",
	 *  match=true,
	 *  message="Your surname must contain only letters"
	 * )
	 * @Assert\Length(
	 *  max = 20,
	 *  maxMessage = "Your surname cannot be longer than {{ limit }} characters"
	 * )
     * @ORM\Column(name="surname", type="string", length=20)
     */
    private $surname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var string
     *
	 * @Assert\Email(strict=true, checkMX=true)
     * @ORM\Column(name="email", type="string", length=100, nullable=true, unique=true)
     */
    private $email;

    /**
     * @var string
     *
	 * @Assert\NotBlank
	 * @Assert\Regex(
	 *  pattern="/^[a-zA-Z]+$/",
	 *  match=true,
	 *  message="Your family name must contain only letters"
	 * )
	 * @Assert\Length(
	 *  max = 20,
	 *  maxMessage = "Your family name cannot be longer than {{ limit }} characters"
	 * )
     * @ORM\Column(name="familly", type="string", length=20)
     */
    private $familly;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $token;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }
    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }
    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }
    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
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
     * Set familly
     *
     * @param string $familly
     *
     * @return User
     */
    public function setFamilly($familly)
    {
        $this->familly = $familly;
        return $this;
    }
    /**
     * Get familly
     *
     * @return string
     */
    public function getFamilly()
    {
        return $this->familly;
    }
    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
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
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
    /**
     * Set token
     *
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
}

