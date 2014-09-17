<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 28.3.14
 * Time: 17:08
 */

namespace Bakalarka\IkarosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class System {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $ID_System;

    /**
     * @ORM\Column(length=30)
     */
    protected $Title;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $CreateDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $DeleteDate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $Lam;

    /**
     * @ORM\Column(type="integer")
     */
    protected $Temp;
    /**
     * @ORM\Column(length=3, nullable=true)
     */
    protected $Environment;
    /**
     * @ORM\Column(length=500, nullable=true)
     */
    protected $Note;

    /**
     * @ORM\OneToMany(targetEntity="PCB", mappedBy="SystemID")
     */
    protected $PCBs;


    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="Systems")
     * @ORM\JoinColumn(name="UserID", referencedColumnName="ID_User")
     */
    protected $UserID;

    public function __construct()
    {
        $this->PCBs= new ArrayCollection();
        $this->CreateDate = new \DateTime();
        $this->Lam = 0;
    }

    /**
     * Get Id_System
     *
     * @return integer 
     */
    public function getIDSystem()
    {
        return $this->ID_System;
    }

    /**
     * Set Title
     *
     * @param string $title
     * @return System
     */
    public function setTitle($title)
    {
        $this->Title = $title;
    
        return $this;
    }

    /**
     * Get Title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->Title;
    }

    /**
     * Set CreateDate
     *
     * @param \DateTime $createDate
     * @return System
     */
    public function setCreateDate($createDate)
    {
        $this->CreateDate = $createDate;
    
        return $this;
    }

    /**
     * Get CreateDate
     *
     * @return string
     */
    public function getCreateDate()    {
        $DateToString = $this->CreateDate->format('d.m.Y'); // for example
        return $DateToString;
    }

    /**
     * Set DeleteDate
     *
     * @param \DateTime $deleteDate
     * @return System
     */
    public function setDeleteDate($deleteDate)
    {
        $this->DeleteDate = $deleteDate;
    
        return $this;
    }

    /**
     * Get DeleteDate
     *
     * @return \DateTime 
     */
    public function getDeleteDate()
    {
        return $this->DeleteDate;
    }

    /**
     * Set Lam
     *
     * @param float $lam
     * @return System
     */
    public function setLam($lam)
    {
        $this->Lam = $lam;
    
        return $this;
    }

    /**
     * Get Lam
     *
     * @return float 
     */
    public function getLam()
    {
        return $this->Lam;
    }

    /**
     * Set Temp
     *
     * @param integer $temp
     * @return System
     */
    public function setTemp($temp)
    {
        $this->Temp = $temp;
    
        return $this;
    }

    /**
     * Get Temp
     *
     * @return integer 
     */
    public function getTemp()
    {
        return $this->Temp;
    }

    /**
     * Add PCBs
     *
     * @param Bakalarka\IkarosBundle\Entity\PCB $pCBs
     * @return System
     */
    public function addPCB(\Bakalarka\IkarosBundle\Entity\PCB $pCBs)
    {
        $this->PCBs[] = $pCBs;
    
        return $this;
    }

    /**
     * Remove PCBs
     *
     * @param Bakalarka\IkarosBundle\Entity\PCB $pCBs
     */
    public function removePCB(\Bakalarka\IkarosBundle\Entity\PCB $pCBs)
    {
        $this->PCBs->removeElement($pCBs);
    }

    /**
     * Get PCBs
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPCBs()
    {
        return $this->PCBs;
    }

    /**
     * Set UserID
     *
     * @param Bakalarka\IkarosBundle\Entity\User $userID
     * @return System
     */
    public function setUserID(\Bakalarka\IkarosBundle\Entity\User $userID = null)
    {
        $this->UserID = $userID;
    
        return $this;
    }

    /**
     * Get UserID
     *
     * @return Bakalarka\IkarosBundle\Entity\User 
     */
    public function getUserID()
    {
        return $this->UserID;
    }



    /**
     * Set Note
     *
     * @param string $note
     * @return System
     */
    public function setNote($note)
    {
        $this->Note = $note;
    
        return $this;
    }

    /**
     * Get Note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->Note;
    }

    /**
     * Set Environment
     *
     * @param string $environment
     * @return System
     */
    public function setEnvironment($environment)
    {
        $this->Environment = $environment;
    
        return $this;
    }

    /**
     * Get Enviroment
     *
     * @return string 
     */
    public function getEnvironment()
    {
        return $this->Environment;
    }
}