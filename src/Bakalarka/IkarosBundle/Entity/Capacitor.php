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
class Capacitor extends Part{
    /**
     * @ORM\Column(type="float")
     */
    protected $Value;
    /**
     * @ORM\Column(type="float")
     */
    protected $VoltageMax;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $VoltageDC;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $VoltageAC;
    /**
     * @ORM\Column(type="float")
     */
    protected $VoltageOperational;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $SerialResistor;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $PassiveTemp;
    /**
     * @ORM\Column(length=7)
     */
    protected $Material;
    /**
     * @ORM\Column(type="float")
     */
    protected $Quality;


    /**
     * @var float $Lam
     */
    protected  $Lam;
    /**
     * @var integer $ID_Part
     */
    protected $ID_Part;

    /**
     * @var string $Label
     */
    protected $Label;

    /**
     * @var integer $Temp
     */
    protected  $Temp;

    /**
     * @var string $Environment
     */
    protected  $Environment;

    /**
     * @var string $Type
     */
    protected  $Type;

    /**
     * @var string $Case
     */
    protected  $CasePart;

    /**
     * @var Bakalarka\IkarosBundle\Entity\PCB
     */
    protected  $PCB_ID;



    /**
     * Set Temp
     *
     * @param float $temp
     * @return Capacitor
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
     * @return Capacitor
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
     * Set PCB_ID
     *
     * @param Bakalarka\IkarosBundle\Entity\PCB $pCBID
     * @return Capacitor
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
     * @param float $value
     * @return Capacitor
     */
    public function setValue($value)
    {
        $this->Value = $value;
    
        return $this;
    }

    /**
     * Get Value
     *
     * @return float 
     */
    public function getValue()
    {
        return $this->Value;
    }



    /**
     * Set VoltageDC
     *
     * @param float $voltageDC
     * @return Capacitor
     */
    public function setVoltageDC($voltageDC)
    {
        $this->VoltageDC = $voltageDC;
    
        return $this;
    }

    /**
     * Get VoltageDC
     *
     * @return float 
     */
    public function getVoltageDC()
    {
        return $this->VoltageDC;
    }

    /**
     * Set VoltageAC
     *
     * @param float $voltageAC
     * @return Capacitor
     */
    public function setVoltageAC($voltageAC)
    {
        $this->VoltageAC = $voltageAC;
    
        return $this;
    }

    /**
     * Get VoltageAC
     *
     * @return float 
     */
    public function getVoltageAC()
    {
        return $this->VoltageAC;
    }



    /**
     * Set SerialResistor
     *
     * @param integer $serialResistor
     * @return Capacitor
     */
    public function setSerialResistor($serialResistor)
    {
        $this->SerialResistor = $serialResistor;
    
        return $this;
    }

    /**
     * Get SerialResistor
     *
     * @return integer 
     */
    public function getSerialResistor()
    {
        return $this->SerialResistor;
    }

    /**
     * Set PassiveTemp
     *
     * @param float $passiveTemp
     * @return Capacitor
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
     * Set Type
     *
     * @param string $type
     * @return Capacitor
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
     * @return Capacitor
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
     * @return Capacitor
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
     * @var \DateTime $CreateDate
     */
    protected $CreateDate;

    /**
     * @var \DateTime $DeleteDate
     */
    protected  $DeleteDate;


    /**
     * Set CreateDate
     *
     * @param \DateTime $createDate
     * @return Capacitor
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
     * @return Capacitor
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
     * Set Environment
     *
     * @param string $environment
     * @return Capacitor
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
     * Set VoltageMax
     *
     * @param float $voltageMax
     * @return Capacitor
     */
    public function setVoltageMax($voltageMax)
    {
        $this->VoltageMax = $voltageMax;
    
        return $this;
    }

    /**
     * Get VoltageMax
     *
     * @return float 
     */
    public function getVoltageMax()
    {
        return $this->VoltageMax;
    }

    /**
     * Set VoltageOperational
     *
     * @param float $voltageOperational
     * @return Capacitor
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
     * Set Material
     *
     * @param string $material
     * @return Capacitor
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

    /**
     * Set Quality
     *
     * @param float $quality
     * @return Capacitor
     */
    public function setQuality($quality)
    {
        $this->Quality = $quality;
    
        return $this;
    }

    /**
     * Get Quality
     *
     * @return floa
     */
    public function getQuality()
    {
        return $this->Quality;
    }
}