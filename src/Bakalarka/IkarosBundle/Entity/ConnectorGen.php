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
class ConnectorGen extends Part{
    /**
     * @ORM\Column(length=30)
     */
    protected $ConnectorType;

    /**
     * @ORM\Column(length=10)
     */
    protected $Quality;

    /**
     * @ORM\Column(type="integer")
     */
    protected $ContactCnt;

    /**
     * @ORM\Column(type="integer")
     */
    protected $MatingFactor;

    /**
     * @ORM\Column(type="integer")
     */
    protected $PassiveTemp;

    /**
     * @ORM\Column(type="float")
     */
    protected $CurrentContact;


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
     * Set ConnectorType
     *
     * @param string $connectorType
     * @return ConnectorGen
     */
    public function setConnectorType($connectorType)
    {
        $this->ConnectorType = $connectorType;
    
        return $this;
    }

    /**
     * Get ConnectorType
     *
     * @return string 
     */
    public function getConnectorType()
    {
        return $this->ConnectorType;
    }

    /**
     * Set Quality
     *
     * @param string $quality
     * @return ConnectorGen
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
     * @return ConnectorGen
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
     * Set MatingFactor
     *
     * @param integer $matingFactor
     * @return ConnectorGen
     */
    public function setMatingFactor($matingFactor)
    {
        $this->MatingFactor = $matingFactor;
    
        return $this;
    }

    /**
     * Get MatingFactor
     *
     * @return integer 
     */
    public function getMatingFactor()
    {
        return $this->MatingFactor;
    }

    /**
     * Set PassiveTemp
     *
     * @param integer $passiveTemp
     * @return ConnectorGen
     */
    public function setPassiveTemp($passiveTemp)
    {
        $this->PassiveTemp = $passiveTemp;
    
        return $this;
    }

    /**
     * Get PassiveTemp
     *
     * @return integer 
     */
    public function getPassiveTemp()
    {
        return $this->PassiveTemp;
    }

    /**
     * Set CurrentContact
     *
     * @param float $currentContact
     * @return ConnectorGen
     */
    public function setCurrentContact($currentContact)
    {
        $this->CurrentContact = $currentContact;
    
        return $this;
    }

    /**
     * Get CurrentContact
     *
     * @return float 
     */
    public function getCurrentContact()
    {
        return $this->CurrentContact;
    }
}