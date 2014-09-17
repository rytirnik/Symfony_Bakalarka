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
class Filter extends Part{
    /**
     * @ORM\Column(length=50)
     */
    protected $FilterType;

    /**
     * @ORM\Column(length=10)
     */
    protected $Quality;


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
     * Set FilterType
     *
     * @param string $filterType
     * @return Filter
     */
    public function setFilterType($filterType)
    {
        $this->FilterType = $filterType;
    
        return $this;
    }

    /**
     * Get FilterType
     *
     * @return string 
     */
    public function getFilterType()
    {
        return $this->FilterType;
    }

    /**
     * Set Quality
     *
     * @param string $quality
     * @return Filter
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
}