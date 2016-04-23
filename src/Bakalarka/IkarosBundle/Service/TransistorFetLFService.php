<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\TransistorFetLF;


class TransistorFetLFService {

    protected $doctrine;

    public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
        $this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
    }

    protected function getRepository() {
        return $this->doctrine->getRepository('BakalarkaIkarosBundle:TransistorFetLF');
    }

//====================================================================================================================
    public function getItems() {
        return $this->getRepository()->findAll();
    }

    public function getItem($id) {
        return $this->getRepository()->find($id);
    }
//====================================================================================================================

//====================================================================================================================

    public function getQualityChoices() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Universal_quality');
        $stmt->execute();
        $QualityAll = $stmt->fetchAll();

        foreach($QualityAll as $q) {
            $QualityChoices[$q['Description']] = $q['Description'];
        }
        return $QualityChoices;
    }

//====================================================================================================================
    public function getQualityValue ($qualityDesc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT qual.Value
                        FROM Universal_quality qual
                        WHERE qual.Description = :desc');
        $stmt->execute(array(':desc' => $qualityDesc));
        $quality = $stmt->fetch();
        return $quality['Value'];
    }
//====================================================================================================================
    public function getActiveTransistors ($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT neco.*, transistor.*
                        FROM TransistorFetLF transistor JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON pcb.ID_PCB=part.PCB_ID
                        WHERE pcb.ID_PCB = :id AND pcb.DeleteDate IS NULL AND part.DeleteDate IS NULL) AS neco
                        ON transistor.ID_Part = neco.ID_Part');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function calculateLam (TransistorFetLF $transistor, $pcbID) {
        $sEnv = $transistor->getEnvironment();
        $piE = $this->systemService->getPiE(64, $sEnv);

        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $temp = $transistor->getTempPassive() + $transistor->getTempDissipation() + $system->getTemp();
        $transistor->setTemp($temp);
        $piT = exp(-1925 * (1/($temp + 273) - 1/298));

        $tech = $transistor->getTechnology();
        if($tech == "JFET")
            $base = 0.0045;
        else
            $base = 0.012;

        $app = $transistor->getApplication();
        $power = $transistor->getPowerRated();
        if($app == "Linear Amplification")
            $piA = 1.5;
        else if ($app == "Small Signal Switching")
            $piA = 0.7;
        else {
            if($power < 2)
                $piA = 1.5;
            else if ($power < 5)
                $piA = 2;
            else if ($power < 50)
                $piA = 4;
            else if ($power < 250)
                $piA = 8;
            else
                $piA = 10;
        }

        $piQ = $this->getQualityValue($transistor->getQuality());

        $lambda = $base * $piT * $piA * $piQ * $piE * pow(10,-6);

        return $lambda;
    }


}