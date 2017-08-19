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
     * @var int
     *
     *  Not mapped field (try!)
     */
    private $chat_id;


    /**
     * @var string
     *
	 * @Assert\Email(strict=true, checkMX=true)
     * @ORM\Column(name="email", type="string", length=100, nullable=true, unique=true)
     */
    private $email;

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
}
