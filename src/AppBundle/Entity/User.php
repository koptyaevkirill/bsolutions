<?php

namespace AppBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table("users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User implements UserInterface, EquatableInterface
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     */
    private $username;
    
    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
    
    /**
     * @var string
     */
    private $passwordRepeat;

    /**
     * @var array
     * @ORM\Column(name="roles", type="array", nullable=true)
     */
    private $roles;

    /**
     * @var string
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;
    
    /**
     * @var integer
     * @ORM\Column(name="personal_number", type="integer", length=255)
     */
    private $personalNumber;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="account_number", type="integer", length=255)
     */
    private $accountNumber;
    
    /**
     * @ORM\Column(name="is_confirm", type="boolean")
     */
    private $isConfirm;
    
    public function __construct() {
        $this->isConfirm = FALSE;
        $this->roles = ['ROLE_ADMIN'];
    }

        /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set accountNumber
     *
     * @param string $accountNumber
     *
     * @return User
     */
    public function setAccountNumber($accountNumber)
    {
        return $this->accountNumber;
    }
    /**
     * Get accountNumber
     *
     * @return integer
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }
    
    /**
     * Set personalNumber
     *
     * @param string $personalNumber
     *
     * @return User
     */
    public function setPersonalNumber($personalNumber)
    {
        return $this->personalNumber;
    }
    /**
     * Get personalNumber
     *
     * @return integer
     */
    public function getPersonalNumber()
    {
        return $this->personalNumber;
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
        return $this->email;
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
     * Set username
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
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
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
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return ['ROLE_ADMIN'];
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
    
    /**
     * Set passwordRepeat
     * @param string $passwordRepeat
     * @return User
     */
    public function setPasswordRepeat($passwordRepeat) {
        $this->passwordRepeat = $passwordRepeat;
        return $this;
    }
    
    /**
     * Get passwordRepeat
     * @return string
     */
    public function getPasswordRepeat() {
        return $this->passwordRepeat;
    }
    
    

    public function eraseCredentials() {
        
    }

    public function isEqualTo(UserInterface $user) {
        if (!$user instanceof WebserviceUser) {
            return false;
        }
        if ($this->password !== $user->getPassword()) {
            return false;
        }
        if ($this->salt !== $user->getSalt()) {
            return false;
        }
        if ($this->email !== $user->getUsername()) {
            return false;
        }
        return true;
    }

}

