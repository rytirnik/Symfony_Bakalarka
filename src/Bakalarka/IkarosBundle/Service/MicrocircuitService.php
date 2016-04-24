<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 23.4.2016
 * Time: 19:41
 */

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\Microcircuit;

class MicrocircuitService
{
    protected $doctrine;

    public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
        $this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
    }

    protected function getRepository() {
        return $this->doctrine->getRepository('BakalarkaIkarosBundle:Microcircuit');
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
    public function getActiveMicrocircuits ($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT neco.*, micro.*
                        FROM Microcircuit micro JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON pcb.ID_PCB=part.PCB_ID
                        WHERE pcb.ID_PCB = :id AND pcb.DeleteDate IS NULL AND part.DeleteDate IS NULL) AS neco
                        ON micro.ID_Part = neco.ID_Part');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function calculateLam (Microcircuit $micro, $pcbID) {
        $sEnv = $micro->getEnvironment();
        $piE = $this->systemService->getPiE(510, $sEnv);

        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $temp = $micro->getTempPassive() + $micro->getTempDissipation() + $system->getTemp();
        $micro->setTemp($temp);

        $packageType = $this->getPackageTypeID($micro->getPackageType());
        $pinCnt = $micro->getPinCount();
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

        $description = $micro->getDescription();
        $app = $micro->getApplication();
        $gateCnt = $micro->getGateCount();
        if($description == "Bipolar") {
            if($app == "Digital") {
                if($gateCnt <= 100)
                    $c1 = 0.0025;
                else if($gateCnt <= 1000)
                    $c1 = 0.005;
                else if($gateCnt <= 3000)
                    $c1 = 0.01;
                else if ($gateCnt <= 10000)
                    $c1 = 0.02;
                else if($gateCnt <= 30000)
                    $c1 = 0.04;
                else
                    $c1 = 0.08;
            }
            else if($app == "Linear") {
                if($gateCnt <= 100)
                    $c1 = 0.01;
                else if ($gateCnt <= 300)
                    $c1 = 0.02;
                else if($gateCnt <= 1000)
                    $c1 = 0.04;
                else
                    $c1 = 0.06;
            }
            else {
                if($gateCnt <= 200)
                    $c1 = 0.01;
                else if($gateCnt <= 1000)
                    $c1 = 0.021;
                else
                    $c1 = 0.042;
            }
        }
        else {
            if($app == "Digital") {
                if($gateCnt <= 100)
                    $c1 = 0.01;
                else if($gateCnt <= 1000)
                    $c1 = 0.02;
                else if($gateCnt <= 3000)
                    $c1 = 0.04;
                else if ($gateCnt <= 10000)
                    $c1 = 0.08;
                else if($gateCnt <= 30000)
                    $c1 = 0.16;
                else
                    $c1 = 0.29;
            }
            else if($app == "Linear") {
                if($gateCnt <= 100)
                    $c1 = 0.01;
                else if ($gateCnt <= 300)
                    $c1 = 0.02;
                else if($gateCnt <= 1000)
                    $c1 = 0.04;
                else
                    $c1 = 0.06;
            }
            else {
                if($gateCnt <= 500)
                    $c1 = 0.00085;
                else if($gateCnt <= 1000)
                    $c1 = 0.0017;
                else if($gateCnt < 5000)
                    $c1 = 0.0034;
                else
                    $c1 = 0.0068;
            }
        }

        $piQ = $this->getQualityValue($micro->getQuality());

        $technology = $this->getTechnology($micro->getTechnology());
        $category = $technology['Category'];
        $ea = $technology['Value'];
        if($category == 1)
            $piT = 0.1 * exp((-$ea / (8.617 * pow(10, -5))) * (1 / ($temp + 273) - 1 / 298));
        else
            $piT = 0.1 * exp((-$ea / (8.617 * pow(10, -5))) * (1 / ($temp + 273) - 1 / 423));

        $years = $micro->getProductionYears();
        $piL = 0.01 * exp(5.35 - 0.35 * $years);

        $lambda = ($c1 * $piT + $c2 * $piE) * $piQ * $piL * pow(10,-6);

        return $lambda;
    }

}