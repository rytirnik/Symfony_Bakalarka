<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 23.4.2016
 * Time: 17:42
 */

namespace Bakalarka\IkarosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Microcircuit extends Part
{
    /**
     * @ORM\Column(length=10)
     */
    protected $Description;

    /**
     * @ORM\Column(length=10)
     */
    protected $Application;

    /**
     * @ORM\Column(type="integer")
     */
    protected $GateCount;

    /**
     * @ORM\Column(length=50)
     */
    protected $Technology;

    /**
     * @ORM\Column(length=20)
     */
    protected $PackageType;

    /**
     * @ORM\Column(type="integer")
     */
    protected $PinCount;

    /**
     * @ORM\Column(type="float")
     */
    protected $ProductionYears;

    /**
     * @ORM\Column(length=10)
     */
    protected $Quality;

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
     * Set Description
     *
     * @param string $description
     * @return Microcircuit
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
     * Set Application
     *
     * @param string $application
     * @return Microcircuit
     */
    public function setApplication($application)
    {
        $this->Application = $application;

        return $this;
    }

    /**
     * Get Application
     *
     * @return string 
     */
    public function getApplication()
    {
        return $this->Application;
    }

    /**
     * Set GateCount
     *
     * @param integer $gateCount
     * @return Microcircuit
     */
    public function setGateCount($gateCount)
    {
        $this->GateCount = $gateCount;

        return $this;
    }

    /**
     * Get GateCount
     *
     * @return integer 
     */
    public function getGateCount()
    {
        return $this->GateCount;
    }

    /**
     * Set Technology
     *
     * @param string $technology
     * @return Microcircuit
     */
    public function setTechnology($technology)
    {
        $this->Technology = $technology;

        return $this;
    }

    /**
     * Get Technology
     *
     * @return string 
     */
    public function getTechnology()
    {
        return $this->Technology;
    }

    /**
     * Set PackageType
     *
     * @param string $packageType
     * @return Microcircuit
     */
    public function setPackageType($packageType)
    {
        $this->PackageType = $packageType;

        return $this;
    }

    /**
     * Get PackageType
     *
     * @return string
     */
    public function getPackageType()
    {
        return $this->PackageType;
    }

    /**
     * Set PinCount
     *
     * @param integer $pinCount
     * @return Microcircuit
     */
    public function setPinCount($pinCount)
    {
        $this->PinCount = $pinCount;

        return $this;
    }

    /**
     * Get PinCount
     *
     * @return integer 
     */
    public function getPinCount()
    {
        return $this->PinCount;
    }

    /**
     * Set ProductionYears
     *
     * @param float $productionYears
     * @return Microcircuit
     */
    public function setProductionYears($productionYears)
    {
        $this->ProductionYears = $productionYears;

        return $this;
    }

    /**
     * Get ProductionYears
     *
     * @return float 
     */
    public function getProductionYears()
    {
        return $this->ProductionYears;
    }

    /**
     * Set Quality
     *
     * @param string $quality
     * @return Microcircuit
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
     * Set TempDissipation
     *
     * @param float $tempDissipation
     * @return Microcircuit
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
     * @return Microcircuit
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
     * @return Microcircuit
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
     * @return Microcircuit
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
     * @return Microcircuit
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
     * @return Microcircuit
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
     * @return Microcircuit
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
     * @return Microcircuit
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
     * @return Microcircuit
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
     * @return Microcircuit
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
     * @return Microcircuit
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

    public function setParams($obj) {
        $this->Label = $obj->Label;
        $this->Environment = $obj->Environment;
        $this->Type = $obj->Type;
        $this->CasePart = $obj->CasePart;
        $this->Quality = $obj->Quality;
        $this->Description = $obj->Description;
        $this->Application = $obj->Application;
        $this->Technology = $obj->Technology;
        $this->TempDissipation = floatval($obj->TempDissipation);
        $this->TempPassive = floatval($obj->TempPassive);
        $this->PinCount = intval($obj->PinCount);
        $this->GateCount = intval($obj->GateCount);
        $this->PackageType = $obj->PackageType;
        $this->ProductionYears = intval($obj->ProductionYears);
    }

    public function to_array() {
        return (array(
            'Label' => $this->Label,
            'CasePart' => $this->CasePart,
            'Type' => $this->Type,
            'Lam' => $this->Lam,
            'Environment' => $this->Environment,
            'Description' => $this->Description,
            'Quality' => $this->Quality,
            'Application' => $this->Application,
            'Technology' => $this->Technology,
            'TempDissipation' => $this->TempDissipation,
            'TempPassive' => $this->TempPassive,
            'PinCount' => $this->PinCount,
            'GateCount' => $this->GateCount,
            'PackageType' => $this->PackageType,
            'ProductionYears' => $this->ProductionYears
        ));
    }
}
