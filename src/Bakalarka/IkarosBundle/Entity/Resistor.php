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
class Resistor extends Part{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $Value;
    /**
     * @ORM\Column(type="float")
     */
    protected $MaxPower;
    /**
     * @ORM\Column(type="float", nullable = true)
     */
    protected $VoltageOperational;
    /**
     * @ORM\Column(type="float", nullable = true)
     */
    protected $CurrentOperational;
    /**
     * @ORM\Column(type="float")
     */
    protected $DissipationPower;
    /**
     * @ORM\Column(type="float")
     */
    protected $DPTemp;

    /**
     * @ORM\Column(type="float")
     */
    protected $PassiveTemp;
    /**
     * @ORM\Column(type="float", nullable = true)
     */
    protected $Alternate;
    /**
     * @ORM\Column(type="float")
     */
    protected $Quality;
    /**
     * @ORM\Column(length=15)
     */
    protected $Material;

    /**
     * @var integer $ID_Part
     */
    protected $ID_Part;

    /**
     * @var string $Label
     */
    protected $Label;


    /**
     * @var string $Type
     */
    protected $Type;

    /**
     * @var string $CasePart
     */
    protected  $CasePart;

    /**
     * @var float $Lam
     */
    protected $Lam;

    /**
     * @var \DateTime $CreateDate
     */
    protected $CreateDate;

    /**
     * @var \DateTime $DeleteDate
     */
    protected $DeleteDate;

    /**
     * @var integer $Temp
     */
    protected  $Temp;

    /**
     * @var string $Environment
     */
    protected  $Environment;



    /**
     * Get Id_Part
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
     * @return Resistor
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
     * @var Bakalarka\IkarosBundle\Entity\PCB
     */
    protected  $PCB_ID;


    /**
     * Set PCB_ID
     *
     * @param Bakalarka\IkarosBundle\Entity\PCB $pCBID
     * @return Resistor
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
     * Set Value
     *
     * @param integer $value
     * @return Resistor
     */
    public function setValue($value)
    {
        $this->Value = $value;
    
        return $this;
    }

    /**
     * Get Value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->Value;
    }

    /**
     * Set MaxPower
     *
     * @param float $maxPower
     * @return Resistor
     */
    public function setMaxPower($maxPower)
    {
        $this->MaxPower = $maxPower;
    
        return $this;
    }

    /**
     * Get MaxPower
     *
     * @return float 
     */
    public function getMaxPower()
    {
        return $this->MaxPower;
    }

    /**
     * Set VoltageOperational
     *
     * @param float $voltageOperational
     * @return Resistor
     */
    public function setVoltageOperational($voltageOperational)
    {
        $this->VoltageOperational = $voltageOperational;
    
        return $this;
    }

    /**
     * Get VoltageOperational
     *
     * @return float 
     */
    public function getVoltageOperational()
    {
        return $this->VoltageOperational;
    }

    /**
     * Set CurrentOperational
     *
     * @param float $currentOperational
     * @return Resistor
     */
    public function setCurrentOperational($currentOperational)
    {
        $this->CurrentOperational = $currentOperational;
    
        return $this;
    }

    /**
     * Get CurrentOperational
     *
     * @return float 
     */
    public function getCurrentOperational()
    {
        return $this->CurrentOperational;
    }

    /**
     * Set DissipationPower
     *
     * @param float $dissipationPower
     * @return Resistor
     */
    public function setDissipationPower($dissipationPower)
    {
        $this->DissipationPower = $dissipationPower;
    
        return $this;
    }

    /**
     * Get DissipationPower
     *
     * @return float 
     */
    public function getDissipationPower()
    {
        return $this->DissipationPower;
    }

    /**
     * Set DPTemp
     *
     * @param float $dPTemp
     * @return Resistor
     */
    public function setDPTemp($dPTemp)
    {
        $this->DPTemp = $dPTemp;
    
        return $this;
    }

    /**
     * Get DPTemp
     *
     * @return float 
     */
    public function getDPTemp()
    {
        return $this->DPTemp;
    }

    /**
     * Set PassiveTemp
     *
     * @param float $passiveTemp
     * @return Resistor
     */
    public function setPassiveTemp($passiveTemp)
    {
        $this->PassiveTemp = $passiveTemp;
    
        return $this;
    }

    /**
     * Get PassiveTemp
     *
     * @return float 
     */
    public function getPassiveTemp()
    {
        return $this->PassiveTemp;
    }

    /**
     * Set Alternate
     *
     * @param float $alternate
     * @return Resistor
     */
    public function setAlternate($alternate)
    {
        $this->Alternate = $alternate;
    
        return $this;
    }

    /**
     * Get Alternate
     *
     * @return float 
     */
    public function getAlternate()
    {
        return $this->Alternate;
    }

    /**
     * Set Type
     *
     * @param string $type
     * @return Resistor
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
     * Set Case
     *
     * @param string $case
     * @return Resistor
     */
    public function setCasePart($casePart)
    {
        $this->CasePart = $casePart;
    
        return $this;
    }

    /**
     * Get Case
     *
     * @return string 
     */
    public function getCasePart()
    {
        return $this->CasePart;
    }



    /**
     * Set Lam
     *
     * @param float $lam
     * @return Resistor
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
     * Set CreateDate
     *
     * @param \DateTime $createDate
     * @return Resistor
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
     * @return Resistor
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
     * Set Temp
     *
     * @param integer $temp
     * @return Resistor
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
     * @return Resistor
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
     * Set Quality
     *
     * @param float $quality
     * @return Resistor
     */
    public function setQuality($quality)
    {
        $this->Quality = $quality;
    
        return $this;
    }

    /**
     * Get Quality
     *
     * @return float 
     */
    public function getQuality()
    {
        return $this->Quality;
    }

    /**
     * Set Material
     *
     * @param string $material
     * @return Resistor
     */
    public function setMaterial($material)
    {
        $this->Material = $material;
    
        return $this;
    }

    /**
     * Get Material
     *
     * @return string 
     */
    public function getMaterial()
    {
        return $this->Material;
    }
}