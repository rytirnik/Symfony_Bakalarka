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
class Switches extends Part{
    /**
     * @ORM\Column(length=30)
     */
    protected $SwitchType;

    /**
     * @ORM\Column(length=10)
     */
    protected $Quality;

    /**
     * @ORM\Column(type="integer")
     */
    protected $ContactCnt;

    /**
     * @ORM\Column(length=10)
     */
    protected $LoadType;

    /**
     * @ORM\Column(type="float")
     */
    protected $OperatingCurrent;

    /**
     * @ORM\Column(type="float")
     */
    protected $RatedResistiveCurrent;


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
     * Set SwitchType
     *
     * @param string $switchType
     * @return Switches
     */
    public function setSwitchType($switchType)
    {
        $this->SwitchType = $switchType;
    
        return $this;
    }

    /**
     * Get SwitchType
     *
     * @return string 
     */
    public function getSwitchType()
    {
        return $this->SwitchType;
    }

    /**
     * Set Quality
     *
     * @param string $quality
     * @return Switches
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
     * Set ContactCnt
     *
     * @param integer $contactCnt
     * @return Switches
     */
    public function setContactCnt($contactCnt)
    {
        $this->ContactCnt = $contactCnt;
    
        return $this;
    }

    /**
     * Get ContactCnt
     *
     * @return integer 
     */
    public function getContactCnt()
    {
        return $this->ContactCnt;
    }

    /**
     * Set LoadType
     *
     * @param string $loadType
     * @return Switches
     */
    public function setLoadType($loadType)
    {
        $this->LoadType = $loadType;
    
        return $this;
    }

    /**
     * Get LoadType
     *
     * @return string 
     */
    public function getLoadType()
    {
        return $this->LoadType;
    }

    /**
     * Set OperatingCurrent
     *
     * @param float $operatingCurrent
     * @return Switches
     */
    public function setOperatingCurrent($operatingCurrent)
    {
        $this->OperatingCurrent = $operatingCurrent;
    
        return $this;
    }

    /**
     * Get OperatingCurrent
     *
     * @return float 
     */
    public function getOperatingCurrent()
    {
        return $this->OperatingCurrent;
    }

    /**
     * Set RatedResistiveCurrent
     *
     * @param float $ratedResistiveCurrent
     * @return Switches
     */
    public function setRatedResistiveCurrent($ratedResistiveCurrent)
    {
        $this->RatedResistiveCurrent = $ratedResistiveCurrent;
    
        return $this;
    }

    /**
     * Get RatedResistiveCurrent
     *
     * @return float 
     */
    public function getRatedResistiveCurrent()
    {
        return $this->RatedResistiveCurrent;
    }
}