<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 23.4.2016
 * Time: 19:41
 */

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\Memory;

class MemoryService
{
    protected $doctrine;

    public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
        $this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
    }

    protected function getRepository() {
        return $this->doctrine->getRepository('BakalarkaIkarosBundle:Memory');
    }

//====================================================================================================================
    public function getItems() {
        return $this->getRepository()->findAll();
    }

    public function getItem($id) {
        return $this->getRepository()->find($id);
    }

//====================================================================================================================
    public function getQualityAll()    {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Microcircuit_quality
                        ORDER BY qualityID');
        $stmt->execute();
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function getQualityChoices()   {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Microcircuit_quality');
        $stmt->execute();
        $QualityAll = $stmt->fetchAll();

        foreach ($QualityAll as $q) {
            $QualityChoices[$q['Description']] = $q['Description'];
        }
        return $QualityChoices;
    }

//====================================================================================================================
    public function getQualityValue ($qualityDesc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT qual.Value
                        FROM Microcircuit_quality qual
                        WHERE qual.Description = :desc');
        $stmt->execute(array(':desc' => $qualityDesc));
        $quality = $stmt->fetch();
        return $quality['Value'];
    }

//====================================================================================================================
    public function getTechnologyChoices()   {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Microcircuit_technology');
        $stmt->execute();
        $techAll = $stmt->fetchAll();

        foreach ($techAll as $q) {
            $techChoices[$q['Description']] = $q['Description'];
        }
        return $techChoices;
    }
//====================================================================================================================
    public function getTechnology ($techDesc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Microcircuit_technology tech
                        WHERE tech.Description = :desc');
        $stmt->execute(array(':desc' => $techDesc));
        $tech = $stmt->fetch();
        return $tech;
    }

//====================================================================================================================
    public function getPackageTypeAll()    {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Microcircuit_packageType');
        $stmt->execute();
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function getPackageTypeChoices()   {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Microcircuit_packageType');
        $stmt->execute();
        $packageAll = $stmt->fetchAll();

        foreach ($packageAll as $q) {
            $packChoices[$q['Description']] = $q['Description'];
        }
        return $packChoices;
    }
//====================================================================================================================
    public function getPackageTypeID ($packageDesc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Microcircuit_packageType package
                        WHERE package.Description = :desc');
        $stmt->execute(array(':desc' => $packageDesc));
        $package = $stmt->fetch();
        return $package['typeID'];
    }
//====================================================================================================================
    public function getTypeChoices($desc = "MOS", $options = 0)   {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT DISTINCT MemoryType
                        FROM Memory_type
                        WHERE Description = :desc');
        $stmt->execute(array(':desc' => $desc));
        $typeAll = $stmt->fetchAll();

        if(!$options) {
            foreach ($typeAll as $q) {
                $typeChoices[$q['MemoryType']] = $q['MemoryType'];
            }
        }
        else {
            $typeChoices = "";
            foreach ($typeAll as $type) {
                $typeChoices = $typeChoices . "<option value='" . $type['MemoryType'] . "'>" . $type['MemoryType'] . "</option>";
            }
        }
        return $typeChoices;
    }
//====================================================================================================================
    public function getTypeValue ($desc, $type, $range) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Memory_type
                        WHERE Description = :desc AND MemoryType = :memType AND RangeID = :range');
        $stmt->execute(array(':desc' => $desc, ':memType' => $type, 'range' => $range));
        $type = $stmt->fetch();
        return $type['RangeValue'];
    }
//====================================================================================================================
    public function getEccChoices () {
        $ecc = array();
        $ecc['No On-Chip ECC'] = 'No On-Chip ECC';
        $ecc['On-Chip Hamming Code'] = 'On-Chip Hamming Code';
        $ecc['Redundant Cell Approach'] = 'Redundant Cell Approach';
        $ecc['Worst case'] = 'Worst case';

        return $ecc;
    }

//====================================================================================================================
    public function getEccValue ($desc) {
        if($desc == 'On-Chip Hamming Code')
            return 0.72;
        else if ($desc == 'Redundant Cell Approach')
            return 0.68;
        else
            return 1;
    }
//====================================================================================================================
    public function getActiveMemories ($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT neco.*, mem.*
                        FROM Memory mem JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON pcb.ID_PCB=part.PCB_ID
                        WHERE pcb.ID_PCB = :id AND pcb.DeleteDate IS NULL AND part.DeleteDate IS NULL) AS neco
                        ON mem.ID_Part = neco.ID_Part');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function calculateLam (Memory $memory, $pcbID) {
        $sEnv = $memory->getEnvironment();
        $piE = $this->systemService->getPiE(510, $sEnv);

        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $temp = $memory->getTempPassive() + $memory->getTempDissipation() + $system->getTemp();
        $memory->setTemp($temp);

        $packageType = $this->getPackageTypeID($memory->getPackageType());
        $pinCnt = $memory->getPinCount();
        switch($packageType){
            case 1:
                $c2 = 2.8 * pow(10,-4) * pow($pinCnt, 1.08);
                break;
            case 2:
                $c2 = 9 * pow(10,-5) * pow($pinCnt, 1.51);
                break;
            case 3:
                $c2 = 3 * pow(10,-5) * pow($pinCnt, 1.82);
                break;
            case 4:
                $c2 = 3 * pow(10,-5) * pow($pinCnt, 2.01);
                break;
            case 5:
                $c2 = 3.6 * pow(10,-4) * pow($pinCnt, 1.08);
                break;
        }

        $piQ = $this->getQualityValue($memory->getQuality());

        $description = $memory->getDescription();
        $memoryType = $memory->getMemoryType();

        $memSize = $memory->getMemorySize();
        if($memSize < 16)
            $range = 1;
        else if($memSize < 64)
            $range = 2;
        else if($memSize < 256)
            $range = 3;
        else
            $range = 4;

        $c1 = $this->getTypeValue($description, $memoryType, $range);
        $oxid = $memory->getEepromOxid();
        $cyclesCnt = $memory->getCyclesCount();

        if($memoryType == "EEPROM") {
            //spocitat cyc
            $piEcc = $this->getEccValue($memory->getECC());

            if($oxid == "Flotox") {
                $a2 = 0;
                $b2 = 0;
                $b1 = pow(($memSize/16), 0.5) * exp(-0.15/(8.617 * pow(10, -5)) * (1/($temp + 273) - 1/333));
                $a1 = 6.817  * pow(10, -6) * $cyclesCnt;
            }
            else {
                if($cyclesCnt < 300000)
                    $a2 = 0;
                else if ($cyclesCnt < 400000)
                    $a2 = 1.1;
                else
                    $a2 = 2.3;

                //zadano v tis.
                $b2 = pow(($memSize/64), 0.25) * exp(0.1/(8.617 * pow(10, -5)) * (1/($temp + 273) - 1/303));
                $b1 = pow(($memSize/64), 0.25) * exp(-0.12/(8.617 * pow(10, -5)) * (1/($temp + 273) - 1/303));

                if($cyclesCnt < 100)
                    $a1 = 0.0097;
                else if ($cyclesCnt < 200)
                    $a1 = 0.014;
                else if ($cyclesCnt < 500)
                    $a1 = 0.023;
                else if ($cyclesCnt < 1000)
                    $a1 = 0.033;
                else if($cyclesCnt < 3000)
                    $a1 = 0.061;
                else if($cyclesCnt < 7000)
                    $a1 = 0.14;
                else
                    $a1 = 0.3;
            }
            $cyc = (($a1 * $b1) + (($a2 * $b2)/$piQ)) / $piEcc;
        }
        else
            $cyc = 0;


        $technology = $this->getTechnology($memory->getTechnology());
        /*$category = $technology['Category'];
        $ea = $technology['Value'];
        if($category == 1)
            $piT = 0.1 * exp((-$ea / (8.617 * pow(10, -5))) * (1 / ($temp + 273) - 1 / 298));
        else
            $piT = 0.1 * exp((-$ea / (8.617 * pow(10, -5))) * (1 / ($temp + 273) - 1 / 423));*/
        //zatim defaultne Memories
        $piT = 0.1 * exp((-0.6 / (8.617 * pow(10, -5))) * (1 / ($temp + 273) - 1 / 298));

        $years = $memory->getProductionYears();
        $piL = 0.01 * exp(5.35 - 0.35 * $years);

        $lambda = ($c1 * $piT + $c2 * $piE + $cyc) * $piQ * $piL * pow(10,-6);

        return $lambda;
    }

}