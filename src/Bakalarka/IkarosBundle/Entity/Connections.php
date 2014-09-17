<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 26.3.14
 * Time: 17:56
 */

namespace Bakalarka\IkarosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bakalarka\IkarosBundle\Entity\Part;

/**
 * @ORM\Entity
 */
class Connections extends Part{
    /**
     * @ORM\Column(type="float")
     */
    protected $ConnectionType;


    /**
     * @var integer $ID_Part
     */
    protected $ID_Part;

    /**
     * @var string $Label
     */
    protected $Label;

    /**
     * @var float $Lam
     */
    protected $Lam;

    /**
     * @var string $Type
     */
    protected $Type;

    /**
     * @var string $CasePart
     */
    protected $CasePart;

    /**
     * @var integer $Temp
     */
    protected $Temp;

    /**
     * @var string $Environment
     */
    protected $Environment;

    /**
     * @var \DateTime $CreateDate
     */
    protected $CreateDate;

    /**
     * @var \DateTime $DeleteDate
     */
    protected $DeleteDate;

    /**
     * @var Bakalarka\IkarosBundle\Entity\PCB
     */
    protected $PCB_ID;




    /**
     * Get ID_Part
     *
     * @return integer 
     */
    public function getIDPart()
    {
        return $this->ID_Part;
    }

    /**
     * Set Label
     *
     * @param string $label
     * @return Fuse
     */
    public function setLabel($label)
    {
        $this->Label = $label;
    
        return $this;
    }

    /**
     * Get Label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->Label;
    }

    /**
     * Set Lam
     *
     * @param float $lam
     * @return Fuse
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
     * Set Type
     *
     * @param string $type
     * @return Fuse
     */
    public function setType($type)
    {
        $this->Type = $type;
    
        return $this;
    }

    /**
     * Get Type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->Type;
    }

    /**
     * Set CasePart
     *
     * @param string $casePart
     * @return Fuse
     */
    public function setCasePart($casePart)
    {
        $this->CasePart = $casePart;
    
        return $this;
    }

    /**
     * Get CasePart
     *
     * @return string 
     */
    public function getCasePart()
    {
        return $this->CasePart;
    }

    /**
     * Set Temp
     *
     * @param integer $temp
     * @return Fuse
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
     * Set Environment
     *
     * @param string $environment
     * @return Fuse
     */
    public function setEnvironment($environment)
    {
        $this->Environment = $environment;
    
        return $this;
    }

    /**
     * Get Environment
     *
     * @return string 
     */
    public function getEnvironment()
    {
        return $this->Environment;
    }

    /**
     * Set CreateDate
     *
     * @param \DateTime $createDate
     * @return Fuse
     */
    public function setCreateDate($createDate)
    {
        $this->CreateDate = $createDate;
    
        return $this;
    }

    /**
     * Get CreateDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->CreateDate;
    }

    /**
     * Set DeleteDate
     *
     * @param \DateTime $deleteDate
     * @return Fuse
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
     * Set PCB_ID
     *
     * @param Bakalarka\IkarosBundle\Entity\PCB $pCBID
     * @return Fuse
     */
    public function setPCBID(\Bakalarka\IkarosBundle\Entity\PCB $pCBID = null)
    {
        $this->PCB_ID = $pCBID;
    
        return $this;
    }

    /**
     * Get PCB_ID
     *
     * @return Bakalarka\IkarosBundle\Entity\PCB 
     */
    public function getPCBID()
    {
        return $this->PCB_ID;
    }

    /**
     * Set ConnectionType
     *
     * @param float $connectionType
     * @return Connection
     */
    public function setConnectionType($connectionType)
    {
        $this->ConnectionType = $connectionType;
    
        return $this;
    }

    /**
     * Get ConnectionType
     *
     * @return float 
     */
    public function getConnectionType()
    {
        return $this->ConnectionType;
    }
}