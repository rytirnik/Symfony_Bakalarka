<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 19.4.2016
 * Time: 19:08
 */

namespace Bakalarka\IkarosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */

class TransistorFetLF extends Part
{
    /**
     * @ORM\Column(length=10)
     */
    protected $Technology;

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
     * Set Technology
     *
     * @param string $technology
     * @return TransistorFetLF
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
     * Set Application
     *
     * @param string $application
     * @return TransistorFetLF
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
     * @return TransistorFetLF
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
     * @param float $powerRated
     * @return TransistorFetLF
     */
    public function setPowerRated($powerRated)
    {
        $this->PowerRated = $powerRated;

        return $this;
    }

    /**
     * Get PowerRated
     *
     * @return float 
     */
    public function getPowerRated()
    {
        return $this->PowerRated;
    }

    /**
     * Set TempDissipation
     *
     * @param float $tempDissipation
     * @return TransistorFetLF
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
     * @return TransistorFetLF
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
     * @return TransistorFetLF
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
     * @return TransistorFetLF
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
     * @return TransistorFetLF
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
     * @return TransistorFetLF
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
     * @return TransistorFetLF
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
     * @return TransistorFetLF
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
     * @return TransistorFetLF
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
     * @return TransistorFetLF
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
     * @return TransistorFetLF
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
        $this->Technology = $obj->Technology;
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
            'Technology' => $this->Technology,
            'TempDissipation' => $this->TempDissipation,
            'TempPassive' => $this->TempPassive,
        ));
    }
}
