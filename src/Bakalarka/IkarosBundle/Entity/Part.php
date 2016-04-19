<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 26.3.14
 * Time: 17:55
 */

namespace Bakalarka\IkarosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="entity_type", type="string")
 * @ORM\DiscriminatorMap({"rezistor" = "Resistor", "kondenzátor" = "Capacitor", "pojistka" = "Fuse",
 *                        "propojení" = "Connections", "konektor, soket" = "ConnectorSoc",
 *                        "konektor, obecný" = "ConnectorGen" , "spínač" = "Switches",
 *                        "filtr" = "Filter", "měřič motohodin" = "RotDevElaps",
 *                        "permaktron" = "TubeWave", "dioda, nízkofrekvenční" = "DiodeLF", "optoelektronika" = "Optoelectronics",
 *                        "krystal" = "Crystal", "tranzistor, bipolární LF" = "TransistorBiLF", "tranzistor, FET LF" = "TransistorFetLF",
 *                        "indukčnost" = "InductiveDev"})
 */
class Part {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $ID_Part;

    /**
     * @ORM\Column(length=64)
     */
    protected $Label;
    /**
     * @ORM\Column(type="float")
     */
    protected $Lam;
    /**
     * @ORM\Column(length=64, nullable=true)
     */
    protected $Type;
    /**
     * @ORM\Column(length=64, nullable=true)
     */
    protected $CasePart;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $Temp;

    /**
     * @ORM\Column(length=3, nullable=true)
     */
    protected $Environment;



    /**
     * @ORM\Column(type="datetime")
     */
    protected $CreateDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $DeleteDate;



    /**
     * @ORM\ManyToOne(targetEntity="PCB", inversedBy="Parts")
     * @ORM\JoinColumn(name="PCB_ID", referencedColumnName="ID_PCB")
     */
    protected $PCB_ID;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->Lam = 0;
        $this->CreateDate = new \DateTime();
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
     * @return Part
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
     * @return Part
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
     * Set Type
     *
     * @param string $type
     * @return Part
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
     * @return Part
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
     * Set Lam
     *
     * @param float $lam
     * @return Part
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
     * @return Part
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
     * @return Part
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
     * @return Part
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
     * @return Part
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
}
