<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 24.4.2016
 * Time: 20:39
 */

namespace Bakalarka\IkarosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */

class Memory extends Part
{
    /**
     * @ORM\Column(length=10)
     */
    protected $Description;

    /**
     * @ORM\Column(length=21)
     */
    protected $MemoryType;

    /**
     * @ORM\Column(type="float")
     */
    protected $MemorySize;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $CyclesCount;

    /**
     * @ORM\Column(length=20, nullable=true)
     */
    protected $EepromOxid;

    /**
     * @ORM\Column(length=30, nullable=true)
     */
    protected $ECC;
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
     * @return Memory
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
     * Set MemoryType
     *
     * @param string $memoryType
     * @return Memory
     */
    public function setMemoryType($memoryType)
    {
        $this->MemoryType = $memoryType;

        return $this;
    }

    /**
     * Get MemoryType
     *
     * @return string 
     */
    public function getMemoryType()
    {
        return $this->MemoryType;
    }

    /**
     * Set MemorySize
     *
     * @param float $memorySize
     * @return Memory
     */
    public function setMemorySize($memorySize)
    {
        $this->MemorySize = $memorySize;

        return $this;
    }

    /**
     * Get MemorySize
     *
     * @return float 
     */
    public function getMemorySize()
    {
        return $this->MemorySize;
    }


    /**
     * Set PackageType
     *
     * @param string $packageType
     * @return Memory
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
     * @return Memory
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
     * @return Memory
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
     * @return Memory
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
     * @return Memory
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
     * @return Memory
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
     * Set CyclesCount
     *
     * @param integer $cyclesCount
     * @return Memory
     */
    public function setCyclesCount($cyclesCount)
    {
        $this->CyclesCount = $cyclesCount;

        return $this;
    }

    /**
     * Get CyclesCount
     *
     * @return integer 
     */
    public function getCyclesCount()
    {
        return $this->CyclesCount;
    }

    /**
     * Set EepromOxid
     *
     * @param string $eepromOxid
     * @return Memory
     */
    public function setEepromOxid($eepromOxid)
    {
        $this->EepromOxid = $eepromOxid;

        return $this;
    }

    /**
     * Get EepromOxid
     *
     * @return string 
     */
    public function getEepromOxid()
    {
        return $this->EepromOxid;
    }

    /**
     * Set ECC
     *
     * @param string $eCC
     * @return Memory
     */
    public function setECC($eCC)
    {
        $this->ECC = $eCC;

        return $this;
    }

    /**
     * Get ECC
     *
     * @return string 
     */
    public function getECC()
    {
        return $this->ECC;
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
     * @return Memory
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
     * @return Memory
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
     * @return Memory
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
     * @return Memory
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
     * @return Memory
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
     * @return Memory
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
     * @return Memory
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
     * @return Memory
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
     * @return Memory
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
        $this->MemoryType = $obj->MemoryType;
        $this->TempDissipation = floatval($obj->TempDissipation);
        $this->TempPassive = floatval($obj->TempPassive);
        $this->PinCount = intval($obj->PinCount);
        $this->MemorySize = floatval($obj->MemorySize);
        $this->PackageType = $obj->PackageType;
        $this->ProductionYears = intval($obj->ProductionYears);
        if($this->MemoryType == "EEPROM") {
            $this->ECC = $obj->ECC;
            $this->EepromOxid = $obj->EepromOxid;
            $this->CyclesCount = intval($obj->CyclesCount);
        }
        else {
            $this->ECC = "-";
            $this->EepromOxid = "-";
            $this->CyclesCount = 0;
        }
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
            'MemoryType' => $this->MemoryType,
            'TempDissipation' => $this->TempDissipation,
            'TempPassive' => $this->TempPassive,
            'PinCount' => $this->PinCount,
            'MemorySize' => $this->MemorySize,
            'PackageType' => $this->PackageType,
            'ProductionYears' => $this->ProductionYears,
            'CyclesCount' => $this->CyclesCount,
            'ECC' => $this->ECC,
            'EepromOxid' => $this->EepromOxid,
        ));
    }
}
