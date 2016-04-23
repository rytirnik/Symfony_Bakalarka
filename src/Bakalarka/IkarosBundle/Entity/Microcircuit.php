<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 23.4.2016
 * Time: 17:42
 */

namespace Bakalarka\IkarosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Microcircuit extends Part
{
    /**
     * @ORM\Column(length=10)
     */
    protected $Description;

    /**
     * @ORM\Column(length=10)
     */
    protected $Application;

    /**
     * @ORM\Column(type="integer")
     */
    protected $GateCount;

    /**
     * @ORM\Column(length=20)
     */
    protected $Technology;

    /**
     * @ORM\Column(type="integer")
     */
    protected $PackageType;

    /**
     * @ORM\Column(type="integer")
     */
    protected $PinCount;

    /**
     * @ORM\Column(type="float")
     */
    protected $ProductionYears;

    /**
     * @ORM\Column(length=10)
     */
    protected $Quality;

    /**
     * @ORM\Column(type="float")
     */
    protected $TempDissipation;

    /**
     * @ORM\Column(type="float")
     */
    protected $TempPassive;

}