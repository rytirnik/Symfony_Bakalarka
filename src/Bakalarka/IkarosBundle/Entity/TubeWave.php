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
class TubeWave extends Part{
    /**
     * @ORM\Column(type="integer")
     */
    protected $Power;

    /**
     * @ORM\Column(type="float")
     */
    protected $Frequency;


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
     * Set Power
     *
     * @param integer $power
     * @return TubeWave
     */
    public function setPower($power)
    {
        $this->Power = $power;
    
        return $this;
    }

    /**
     * Get Power
     *
     * @return integer 
     */
    public function getPower()
    {
        return $this->Power;
    }

    /**
     * Set Frequency
     *
     * @param float $frequency
     * @return TubeWave
     */
    public function setFrequency($frequency)
    {
        $this->Frequency = $frequency;
    
        return $this;
    }

    /**
     * Get Frequency
     *
     * @return float 
     */
    public function getFrequency()
    {
        return $this->Frequency;
    }

    public function setParams($obj) {
        $this->Label = $obj->Label;
        $this->Environment = $obj->Environment;
        $this->Type = $obj->Type;
        $this->CasePart = $obj->CasePart;
        $this->Power = $obj->Power;
        $this->Frequency = $obj->Frequency;
    }

    public function to_array() {
        return (array(
            'Label' => $this->Label,
            'Power' => $this->Power,
            'CasePart' => $this->CasePart,
            'Type' => $this->Type,
            'Lam' => $this->Lam,
            'Environment' => $this->Environment,
            'Frequency' => $this->Frequency
        ));
    }
}