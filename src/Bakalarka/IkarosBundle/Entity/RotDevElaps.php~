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
class RotDevElaps extends Part{
    /**
     * @ORM\Column(type="integer")
     */
    protected $TempOperational;

    /**
     * @ORM\Column(type="integer")
     */
    protected $TempMax;

    /**
     * @ORM\Column(length=20)
     */
    protected $DevType;


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
     * Set TempOperational
     *
     * @param integer $tempOperational
     * @return RotDevElaps
     */
    public function setTempOperational($tempOperational)
    {
        $this->TempOperational = $tempOperational;
    
        return $this;
    }

    /**
     * Get TempOperational
     *
     * @return integer 
     */
    public function getTempOperational()
    {
        return $this->TempOperational;
    }

    /**
     * Set TempMax
     *
     * @param integer $tempMax
     * @return RotDevElaps
     */
    public function setTempMax($tempMax)
    {
        $this->TempMax = $tempMax;
    
        return $this;
    }

    /**
     * Get TempMax
     *
     * @return integer 
     */
    public function getTempMax()
    {
        return $this->TempMax;
    }

    /**
     * Set DevType
     *
     * @param string $devType
     * @return RotDevElaps
     */
    public function setDevType($devType)
    {
        $this->DevType = $devType;
    
        return $this;
    }

    /**
     * Get DevType
     *
     * @return string 
     */
    public function getDevType()
    {
        return $this->DevType;
    }
}