<?php

namespace Bakalarka\IkarosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="Mail", message="Zadaná e-mailová adresa už je používána.")
 * @UniqueEntity(fields="Username", message="Zadané uživatelské jméno už je používáno.")  
 */
class User implements UserInterface {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true) 
     * @ORM\GeneratedValue(strategy="AUTO")         
     */
    protected $ID_User;
    /**
     * @ORM\Column(length=64, unique=true)
     */
    protected $Username;
    /**
     * @ORM\Column(length=255)
     */
    protected $Password;
     /**
     * @ORM\Column(length=32, unique=true)
     */
    protected $Mail;
    /**
     * @ORM\Column(length=32)
     */
    protected $Role;   
    /**
     * @ORM\Column(length=255)
     */
    protected $Salt;

    /**
     * @ORM\OneToMany(targetEntity="System", mappedBy="UserID")
     */
    protected $Systems;

    public function __construct() {
        $this->Salt = md5(uniqid(null, true));
        $this->Role = 'ROLE_USER';
        $this->Systems = new ArrayCollection();
    }        

    /**
     * @inheritDoc
     */

    public function getUsername()
    {
        return $this->Username;
    }
    /**
     * @inheritDoc
     */

    public function getMail()
    {
        return $this->Mail;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->Password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array($this->Role);
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * Set ID_User
     *
     * @param integer $iDUser
     * @return User
     */
    public function setIDUser($iDUser)
    {
        $this->ID_User = $iDUser;
    
        return $this;
    }

    /**
     * Get ID_User
     *
     * @return integer 
     */
    public function getIDUser()
    {
        return $this->ID_User;
    }

    /**
     * Set Username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->Username = $username;
    
        return $this;
    }

    /**
     * Set Password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->Password = $password;
    
        return $this;
    }
    
    /**
     * Set Mail
     *
     * @param string $mail
     * @return User
     */
    public function setMail($mail)
    {
        $this->Mail = $mail;
    
        return $this;
    }

    /**
     * Set Role
     *
     * @param string $role
     * @return User
     */
    public function setRole($role)
    {
        $this->Role = $role;
    
        return $this;
    }

    /**
     * Get Role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->Role;
    }

    /**
     * Set Salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->Salt = $salt;
    
        return $this;
    }

    /**
     * Add Systems
     *
     * @param Bakalarka\IkarosBundle\Entity\System $systems
     * @return User
     */
    public function addSystem(\Bakalarka\IkarosBundle\Entity\System $systems)
    {
        $this->Systems[] = $systems;
    
        return $this;
    }

    /**
     * Remove Systems
     *
     * @param Bakalarka\IkarosBundle\Entity\System $systems
     */
    public function removeSystem(\Bakalarka\IkarosBundle\Entity\System $systems)
    {
        $this->Systems->removeElement($systems);
    }

    /**
     * Get Systems
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSystems()
    {
        return $this->Systems;
    }
}
