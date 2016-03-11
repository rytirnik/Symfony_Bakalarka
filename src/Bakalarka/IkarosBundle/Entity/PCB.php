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
class PCB {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $ID_PCB;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $Label;

    /**
     * @ORM\Column(type="integer")
     */
    protected $Lifetime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $Quality;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $Layers;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $SolderingPointAuto;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $SolderingPointHand;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $Lam;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $SumLam;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $SumPartsLam;



    /**
     * @ORM\ManyToOne(targetEntity="System", inversedBy="PCBs")
     * @ORM\JoinColumn(name="SystemID", referencedColumnName="ID_System")
     */
    protected $SystemID;

    /**
     * @ORM\OneToMany(targetEntity="Part", mappedBy="PCB_ID")
     */
    protected $Parts;

    /**
     * @ORM\OneToMany(targetEntity="PartSMT", mappedBy="PCB_ID")
     */
    protected $SMTParts;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $EquipType;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $SubstrateMaterial;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $CreateDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $DeleteDate;




    /**
     * Get Id_PCB
     *
     * @return integer 
     */
    public function getIDPCB()
    {
        return $this->ID_PCB;
    }

    /**
     * Set Lifetime
     *
     * @param integer $lifetime
     * @return PCB
     */
    public function setLifetime($lifetime)
    {
        $this->Lifetime = $lifetime;
    
        return $this;
    }

    /**
     * Get Lifetime
     *
     * @return integer 
     */
    public function getLifetime()
    {
        return $this->Lifetime;
    }



    /**
     * Set Layers
     *
     * @param integer $layers
     * @return PCB
     */
    public function setLayers($layers)
    {
        $this->Layers = $layers;
    
        return $this;
    }

    /**
     * Get Layers
     *
     * @return integer 
     */
    public function getLayers()
    {
        return $this->Layers;
    }

    /**
     * Set SolderingPointAuto
     *
     * @param integer $solderingPointAuto
     * @return PCB
     */
    public function setSolderingPointAuto($solderingPointAuto)
    {
        $this->SolderingPointAuto = $solderingPointAuto;
    
        return $this;
    }

    /**
     * Get SolderingPointAuto
     *
     * @return integer 
     */
    public function getSolderingPointAuto()
    {
        return $this->SolderingPointAuto;
    }

    /**
     * Set SolderingPointHand
     *
     * @param integer $solderingPointHand
     * @return PCB
     */
    public function setSolderingPointHand($solderingPointHand)
    {
        $this->SolderingPointHand = $solderingPointHand;
    
        return $this;
    }

    /**
     * Get SolderingPointHand
     *
     * @return integer 
     */
    public function getSolderingPointHand()
    {
        return $this->SolderingPointHand;
    }

    /**
     * Set Lam
     *
     * @param float $lam
     * @return PCB
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
     * Set SystemID
     *
     * @param Bakalarka\IkarosBundle\Entity\System $systemID
     * @return PCB
     */
    public function setSystemID(\Bakalarka\IkarosBundle\Entity\System $systemID = null)
    {
        $this->SystemID = $systemID;
    
        return $this;
    }

    /**
     * Get SystemID
     *
     * @return Bakalarka\IkarosBundle\Entity\System 
     */
    public function getSystemID()
    {
        return $this->SystemID;
    }

    /**
     * Set Label
     *
     * @param string $label
     * @return PCB
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
     * Constructor
     */
    public function __construct()
    {
        $this->Parts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isActive = true;
        $this->Lam = 0;
        $this->SumLam = 0;
        $this->SumPartsLam = 0;
        $this->CreateDate = new \DateTime();
    }
    
    /**
     * Add Parts
     *
     * @param Bakalarka\IkarosBundle\Entity\Part $parts
     * @return PCB
     */
    public function addPart(\Bakalarka\IkarosBundle\Entity\Part $parts)
    {
        $this->Parts[] = $parts;
    
        return $this;
    }

    /**
     * Remove Parts
     *
     * @param Bakalarka\IkarosBundle\Entity\Part $parts
     */
    public function removePart(\Bakalarka\IkarosBundle\Entity\Part $parts)
    {
        $this->Parts->removeElement($parts);
    }

    /**
     * Get Parts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getParts()
    {
        return $this->Parts;
    }






    /**
     * Set Quality
     *
     * @param integer $quality
     * @return PCB
     */
    public function setQuality($quality)
    {
        $this->Quality = $quality;
    
        return $this;
    }

    /**
     * Get Quality
     *
     * @return integer 
     */
    public function getQuality()
    {
        return $this->Quality;
    }

    /**
     * Set EquipType
     *
     * @param string $equipType
     * @return PCB
     */
    public function setEquipType($equipType)
    {
        $this->EquipType = $equipType;
    
        return $this;
    }

    /**
     * Get EquipType
     *
     * @return string
     */
    public function getEquipType()
    {
        return $this->EquipType;
    }

    /**
     * Set SubstrateMaterial
     *
     * @param string $substrateMaterial
     * @return PCB
     */
    public function setSubstrateMaterial($substrateMaterial)
    {
        $this->SubstrateMaterial = $substrateMaterial;
    
        return $this;
    }

    /**
     * Get SubstrateMaterial
     *
     * @return string
     */
    public function getSubstrateMaterial()
    {
        return $this->SubstrateMaterial;
    }

    /**
     * Add SMTParts
     *
     * @param Bakalarka\IkarosBundle\Entity\PartSMT $sMTParts
     * @return PCB
     */
    public function addSMTPart(\Bakalarka\IkarosBundle\Entity\PartSMT $sMTParts)
    {
        $this->SMTParts[] = $sMTParts;
    
        return $this;
    }

    /**
     * Remove SMTParts
     *
     * @param Bakalarka\IkarosBundle\Entity\PartSMT $sMTParts
     */
    public function removeSMTPart(\Bakalarka\IkarosBundle\Entity\PartSMT $sMTParts)
    {
        $this->SMTParts->removeElement($sMTParts);
    }

    /**
     * Get SMTParts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSMTParts()
    {
        return $this->SMTParts;
    }

    /**
     * Set SumLam
     *
     * @param float $sumLam
     * @return PCB
     */
    public function setSumLam($sumLam)
    {
        $this->SumLam = $sumLam;
    
        return $this;
    }

    /**
     * Get SumLam
     *
     * @return float 
     */
    public function getSumLam()
    {
        return $this->SumLam;
    }

    /**
     * Set SumPartsLam
     *
     * @param float $sumPartsLam
     * @return PCB
     */
    public function setSumPartsLam($sumPartsLam)
    {
        $this->SumPartsLam = $sumPartsLam;
    
        return $this;
    }

    /**
     * Get SumPartsLam
     *
     * @return float 
     */
    public function getSumPartsLam()
    {
        return $this->SumPartsLam;
    }

    /**
     * Set CreateDate
     *
     * @param \DateTime $createDate
     * @return PCB
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
     * @return PCB
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
