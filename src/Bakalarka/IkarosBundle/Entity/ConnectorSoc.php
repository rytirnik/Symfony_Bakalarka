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
class ConnectorSoc extends Part{
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
    protected $ActivePins;


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
     * @return ConnectorSoc
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
     * @return ConnectorSoc
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
     * Set ActivePins
     *
     * @param integer $activePins
     * @return ConnectorSoc
     */
    public function setActivePins($activePins)
    {
        $this->ActivePins = $activePins;
    
        return $this;
    }

    /**
     * Get ActivePins
     *
     * @return integer 
     */
    public function getActivePins()
    {
        return $this->ActivePins;
    }

    public function setParams($obj) {
        $this->Label = $obj->Label;
        $this->Environment = $obj->Environment;
        $this->ConnectorType = $obj->ConnectorType;
        $this->CasePart = $obj->CasePart;
        $this->Quality = $obj->Quality;
        $this->ActivePins = intval($obj->ActivePins);
        $this->Type = $obj->Type;
    }

    public function to_array() {
        return (array(
            'Label' => $this->Label,
            'CasePart' => $this->CasePart,
            'Type' => $this->Type,
            'Lam' => $this->Lam,
            'Environment' => $this->Environment,
            'ConnectorType' => $this->ConnectorType,
            'Quality' => $this->Quality,
            'ActivePins' => $this->ActivePins

        ));
    }
}