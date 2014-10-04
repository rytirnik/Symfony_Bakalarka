<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 28.3.14
 * Time: 17:19
 */

namespace Bakalarka\IkarosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class PartSMT {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $ID_Part_SMT;

    /**
     * @ORM\Column(type="integer")
     */
    protected $LeadConfig;

    /**
     * @ORM\Column(type="integer")
     */
    protected $TCEPackage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $Height;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $Width;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $TempDissipation;     

    /**
     * @ORM\Column(type="integer")
     */
    protected $Cnt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $Lam;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $CreateDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $DeleteDate;


    /**
     * @ORM\ManyToOne(targetEntity="PCB", inversedBy="SMTParts")
     * @ORM\JoinColumn(name="PCB_ID", referencedColumnName="ID_PCB")
     */
    protected $PCB_ID;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Lam = 0;
        $this->CreateDate = new \DateTime();
    }


    /**
     * Get ID_Part_SMT
     *
     * @return integer 
     */
    public function getIDPartSMT()
    {
        return $this->ID_Part_SMT;
    }

    /**
     * Set LeadConfig
     *
     * @param integer $leadConfig
     * @return PartSMT
     */
    public function setLeadConfig($leadConfig)
    {
        $this->LeadConfig = $leadConfig;
    
        return $this;
    }

    /**
     * Get LeadConfig
     *
     * @return integer 
     */
    public function getLeadConfig()
    {
        return $this->LeadConfig;
    }

    /**
     * Set TCEPackage
     *
     * @param integer $tCEPackage
     * @return PartSMT
     */
    public function setTCEPackage($tCEPackage)
    {
        $this->TCEPackage = $tCEPackage;
    
        return $this;
    }

    /**
     * Get TCEPackage
     *
     * @return integer 
     */
    public function getTCEPackage()
    {
        return $this->TCEPackage;
    }

    /**
     * Set Height
     *
     * @param integer $height
     * @return PartSMT
     */
    public function setHeight($height)
    {
        $this->Height = $height;
    
        return $this;
    }

    /**
     * Get Height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->Height;
    }

    /**
     * Set Width
     *
     * @param integer $width
     * @return PartSMT
     */
    public function setWidth($width)
    {
        $this->Width = $width;
    
        return $this;
    }

    /**
     * Get Width
     *
     * @return integer 
     */
    public function getWidth()
    {
        return $this->Width;
    }



    /**
     * Set Cnt
     *
     * @param integer $cnt
     * @return PartSMT
     */
    public function setCnt($cnt)
    {
        $this->Cnt = $cnt;
    
        return $this;
    }

    /**
     * Get Cnt
     *
     * @return integer 
     */
    public function getCnt()
    {
        return $this->Cnt;
    }

    /**
     * Set Lam
     *
     * @param float $lam
     * @return PartSMT
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
     * Set PCB_ID
     *
     * @param Bakalarka\IkarosBundle\Entity\PCB $pCBID
     * @return PartSMT
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
     * Set TempDissipation
     *
     * @param integer $tempDissipation
     * @return PartSMT
     */
    public function setTempDissipation($tempDissipation)
    {
        $this->TempDissipation = $tempDissipation;
    
        return $this;
    }

    /**
     * Get TempDissipation
     *
     * @return integer 
     */
    public function getTempDissipation()
    {
        return $this->TempDissipation;
    }

    /**
     * Set CreateDate
     *
     * @param \DateTime $createDate
     * @return PartSMT
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
     * @return PartSMT
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

    public function setParams($obj) {
        $this->Label = $obj->Label;

    }
}