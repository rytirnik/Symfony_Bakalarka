<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 19.4.2016
 * Time: 20:08
 */

namespace Bakalarka\IkarosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class InductiveDev extends Part
{
    /**
     * @ORM\Column(length=15)
     */
    protected $DevType;

    /**
     * @ORM\Column(length=30)
     */
    protected $Description;

    /**
     * @ORM\Column(length=10)
     */
    protected $Quality;

    /**
     * @ORM\Column(type="float")
     */
    protected $PowerLoss;

    /**
     * @ORM\Column(type="float")
     */
    protected $Weight;

    /**
     * @ORM\Column(type="float")
     */
    protected $Surface;

    /**
     * @ORM\Column(type="float")
     */
    protected $TempDissipation;

    /**
     * @ORM\Column(type="float")
     */
    protected $TempPassive;
    /**
     * @var integer
     */
    protected $ID_Part;

    /**
     * @var string
     */
    protected $Label;

    /**
     * @var float
     */
    protected $Lam;

    /**
     * @var string
     */
    protected $Type;

    /**
     * @var string
     */
    protected $CasePart;

    /**
     * @var float
     */
    protected $Temp;

    /**
     * @var string
     */
    protected $Environment;

    /**
     * @var \DateTime
     */
    protected $CreateDate;

    /**
     * @var \DateTime
     */
    protected $DeleteDate;

    /**
     * @var \Bakalarka\IkarosBundle\Entity\PCB
     */
    protected $PCB_ID;


    /**
     * Set DevType
     *
     * @param string $devType
     * @return InductiveDev
     */
    public function setDevType($devType)
    {
        $this->DevType = $devType;

        return $this;
    }

    /**
     * Get DevType
     *
     * @return string 
     */
    public function getDevType()
    {
        return $this->DevType;
    }

    /**
     * Set Description
     *
     * @param string $description
     * @return InductiveDev
     */
    public function setDescription($description)
    {
        $this->Description = $description;

        return $this;
    }

    /**
     * Get Description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * Set Quality
     *
     * @param string $quality
     * @return InductiveDev
     */
    public function setQuality($quality)
    {
        $this->Quality = $quality;

        return $this;
    }

    /**
     * Get Quality
     *
     * @return string 
     */
    public function getQuality()
    {
        return $this->Quality;
    }

    /**
     * Set PowerLoss
     *
     * @param float $powerLoss
     * @return InductiveDev
     */
    public function setPowerLoss($powerLoss)
    {
        $this->PowerLoss = $powerLoss;

        return $this;
    }

    /**
     * Get PowerLoss
     *
     * @return float 
     */
    public function getPowerLoss()
    {
        return $this->PowerLoss;
    }

    /**
     * Set Weight
     *
     * @param float $weight
     * @return InductiveDev
     */
    public function setWeight($weight)
    {
        $this->Weight = $weight;

        return $this;
    }

    /**
     * Get Weight
     *
     * @return float 
     */
    public function getWeight()
    {
        return $this->Weight;
    }

    /**
     * Set Surface
     *
     * @param float $surface
     * @return InductiveDev
     */
    public function setSurface($surface)
    {
        $this->Surface = $surface;

        return $this;
    }

    /**
     * Get Surface
     *
     * @return float 
     */
    public function getSurface()
    {
        return $this->Surface;
    }

    /**
     * Set TempDissipation
     *
     * @param float $tempDissipation
     * @return InductiveDev
     */
    public function setTempDissipation($tempDissipation)
    {
        $this->TempDissipation = $tempDissipation;

        return $this;
    }

    /**
     * Get TempDissipation
     *
     * @return float 
     */
    public function getTempDissipation()
    {
        return $this->TempDissipation;
    }

    /**
     * Set TempPassive
     *
     * @param float $tempPassive
     * @return InductiveDev
     */
    public function setTempPassive($tempPassive)
    {
        $this->TempPassive = $tempPassive;

        return $this;
    }

    /**
     * Get TempPassive
     *
     * @return float 
     */
    public function getTempPassive()
    {
        return $this->TempPassive;
    }

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
     * @return InductiveDev
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
     * @return InductiveDev
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
     * @return InductiveDev
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
     * @return InductiveDev
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
     * @param float $temp
     * @return InductiveDev
     */
    public function setTemp($temp)
    {
        $this->Temp = $temp;

        return $this;
    }

    /**
     * Get Temp
     *
     * @return float 
     */
    public function getTemp()
    {
        return $this->Temp;
    }

    /**
     * Set Environment
     *
     * @param string $environment
     * @return InductiveDev
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
     * @return InductiveDev
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
     * @return InductiveDev
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
     * @param \Bakalarka\IkarosBundle\Entity\PCB $pCBID
     * @return InductiveDev
     */
    public function setPCBID(\Bakalarka\IkarosBundle\Entity\PCB $pCBID = null)
    {
        $this->PCB_ID = $pCBID;

        return $this;
    }

    /**
     * Get PCB_ID
     *
     * @return \Bakalarka\IkarosBundle\Entity\PCB 
     */
    public function getPCBID()
    {
        return $this->PCB_ID;
    }
}
