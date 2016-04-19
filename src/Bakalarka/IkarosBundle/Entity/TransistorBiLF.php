<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 19.4.2016
 * Time: 17:03
 */

namespace Bakalarka\IkarosBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */

class TransistorBiLF extends Part
{
    /**
     * @ORM\Column(length=25)
     */
    protected $Application;

    /**
     * @ORM\Column(length=10)
     */
    protected $Quality;

    /**
     * @ORM\Column(type="float")
     */
    protected $PowerRated;

    /**
     * @ORM\Column(type="float")
     */
    protected $VoltageCE;

    /**
     * @ORM\Column(type="float")
     */
    protected $VoltageCEO;

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
     * Set Application
     *
     * @param string $application
     * @return TransistorBiLF
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
     * Set Quality
     *
     * @param string $quality
     * @return TransistorBiLF
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
     * Set PowerRated
     *
     * @param \number $powerRated
     * @return TransistorBiLF
     */
    public function setPowerRated(\number $powerRated)
    {
        $this->PowerRated = $powerRated;

        return $this;
    }

    /**
     * Get PowerRated
     *
     * @return \number 
     */
    public function getPowerRated()
    {
        return $this->PowerRated;
    }

    /**
     * Set VoltageCE
     *
     * @param \number $voltageCE
     * @return TransistorBiLF
     */
    public function setVoltageCE(\number $voltageCE)
    {
        $this->VoltageCE = $voltageCE;

        return $this;
    }

    /**
     * Get VoltageCE
     *
     * @return \number 
     */
    public function getVoltageCE()
    {
        return $this->VoltageCE;
    }

    /**
     * Set VoltageCEO
     *
     * @param \number $voltageCEO
     * @return TransistorBiLF
     */
    public function setVoltageCEO(\number $voltageCEO)
    {
        $this->VoltageCEO = $voltageCEO;

        return $this;
    }

    /**
     * Get VoltageCEO
     *
     * @return \number 
     */
    public function getVoltageCEO()
    {
        return $this->VoltageCEO;
    }

    /**
     * Set TempDissipation
     *
     * @param \number $tempDissipation
     * @return TransistorBiLF
     */
    public function setTempDissipation(\number $tempDissipation)
    {
        $this->TempDissipation = $tempDissipation;

        return $this;
    }

    /**
     * Get TempDissipation
     *
     * @return \number 
     */
    public function getTempDissipation()
    {
        return $this->TempDissipation;
    }

    /**
     * Set TempPassive
     *
     * @param \number $tempPassive
     * @return TransistorBiLF
     */
    public function setTempPassive(\number $tempPassive)
    {
        $this->TempPassive = $tempPassive;

        return $this;
    }

    /**
     * Get TempPassive
     *
     * @return \number 
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
     * @return TransistorBiLF
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
     * @return TransistorBiLF
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
     * @return TransistorBiLF
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
     * @return TransistorBiLF
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
     * @return TransistorBiLF
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
     * @return TransistorBiLF
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
     * @return TransistorBiLF
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
     * @return TransistorBiLF
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
     * @return TransistorBiLF
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
        $this->Application = $obj->Application;
        $this->PowerRated = floatval($obj->PowerRated);
        $this->VoltageCE = floatval($obj->VoltageCE);
        $this->VoltageCEO = floatval($obj->VoltageCEO);
        $this->TempDissipation = floatval($obj->TempDissipation);
        $this->TempPassive = floatval($obj->TempPassive);
    }

    public function to_array() {
        return (array(
            'Label' => $this->Label,
            'CasePart' => $this->CasePart,
            'Type' => $this->Type,
            'Lam' => $this->Lam,
            'Environment' => $this->Environment,
            'Application' => $this->Application,
            'Quality' => $this->Quality,
            'PowerRated' => $this->PowerRated,
            'VoltageCE' => $this->VoltageCE,
            'VoltageCEO' => $this->VoltageCEO,
            'TempDissipation' => $this->TempDissipation,
            'TempPassive' => $this->TempPassive,
        ));
    }

}
