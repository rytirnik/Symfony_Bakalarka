<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 7.3.16
 * Time: 19:36
 */

namespace Bakalarka\IkarosBundle\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class DiodeLF extends Part{
    /**
     * @ORM\Column(length=60)
     */
    protected $Application;
    /**
     * @ORM\Column(length=10)
     */
    protected $Quality;
    /**
     * @ORM\Column(type="integer")
     */
    protected $ContactConstruction;
    /**
     * @ORM\Column(type="float")
     */
    protected $VoltageRated;
    /**
     * @ORM\Column(type="float")
     */
    protected $VoltageApplied;
    /**
     * @ORM\Column(type="float")
     */
    protected $DPTemp;
    /**
     * @ORM\Column(type="float")
     */
    protected $PassiveTemp;

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
     * @var integer
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
     * @return neco
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
     * @return neco
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
     * @return neco
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
     * @return neco
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
     * @return neco
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
     * @return neco
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
     * @return neco
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
     * @return neco
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
     * @return neco
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

    /**
     * Set Application
     *
     * @param string $application
     * @return DiodeLF
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
     * @return DiodeLF
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
     * Set ContactConstruction
     *
     * @param integer $contactConstruction
     * @return DiodeLF
     */
    public function setContactConstruction($contactConstruction)
    {
        $this->ContactConstruction = $contactConstruction;

        return $this;
    }

    /**
     * Get ContactConstruction
     *
     * @return integer 
     */
    public function getContactConstruction()
    {
        return $this->ContactConstruction;
    }

    /**
     * Set VoltageRated
     *
     * @param float $voltageRated
     * @return DiodeLF
     */
    public function setVoltageRated($voltageRated)
    {
        $this->VoltageRated = $voltageRated;

        return $this;
    }

    /**
     * Get VoltageRated
     *
     * @return float 
     */
    public function getVoltageRated()
    {
        return $this->VoltageRated;
    }

    /**
     * Set VoltageApplied
     *
     * @param float $voltageApplied
     * @return DiodeLF
     */
    public function setVoltageApplied($voltageApplied)
    {
        $this->VoltageApplied = $voltageApplied;

        return $this;
    }

    /**
     * Get VoltageApplied
     *
     * @return float 
     */
    public function getVoltageApplied()
    {
        return $this->VoltageApplied;
    }

    /**
     * Set DPTemp
     *
     * @param float $dPTemp
     * @return DiodeLF
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
     * @return DiodeLF
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

    public function setParams($obj) {
        $this->Label = $obj->Label;
        $this->Environment = $obj->Environment;
        $this->Type = $obj->Type;
        $this->CasePart = $obj->CasePart;
        $this->Quality = $obj->Quality;
        $this->Application = $obj->Application;
        $this->ContactConstruction = intval($obj->ContactConstruction);
        $this->DPTemp = floatval($obj->DPTemp);
        $this->PassiveTemp = floatval($obj->PassiveTemp);
        $this->VoltageApplied = floatval($obj->VoltageApplied);
        $this->VoltageRated = floatval($obj->VoltageRated);

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
            'ContactConstruction' => $this->ContactConstruction,
            'DPTemp' => $this->DPTemp,
            'PassiveTemp' => $this->PassiveTemp,
            'VoltageApplied' => $this->VoltageApplied,
            'VoltageRated' => $this->VoltageRated
        ));
    }
}
