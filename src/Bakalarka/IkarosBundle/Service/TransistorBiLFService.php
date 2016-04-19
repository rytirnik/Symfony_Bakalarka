<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\TransistorBiLF;


class TransistorBiLFService {

    protected $doctrine;

    public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
        $this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
    }

    protected function getRepository() {
        return $this->doctrine->getRepository('BakalarkaIkarosBundle:TransistorBiLF');
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
                        FROM QualityUniversal');
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
                        FROM QualityUniversal qual
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
                        FROM TransistorBiLF transistor JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON pcb.ID_PCB=part.PCB_ID
                        WHERE pcb.ID_PCB = :id AND pcb.DeleteDate IS NULL AND part.DeleteDate IS NULL) AS neco
                        ON transistor.ID_Part = neco.ID_Part');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function calculateLam (TransistorBiLF $transistor, $pcbID) {
        $sEnv = $transistor->getEnvironment();
        $piE = $this->systemService->getPiE(63, $sEnv);

        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $temp = $transistor->getTempPassive() + $transistor->getTempDissipation() + $system->getTemp();
        $transistor->setTemp($temp);
        $piT = exp(-2114 * (1/($temp + 273) - 1/298));

        $base = 0.00074;
        $app = $transistor->getApplication();
        if($app == "Switching")
            $piA = 0.7;
        else
            $piA = 1.5;

        $piQ = $this->getQualityValue($transistor->getQuality());

        $power = $transistor->getPowerRated();
        if($power <= 0.1)
            $piR = 0.43;
        else
            $piR = pow($power, 0.37);

        $vs = $transistor->getVoltageCE() / $transistor->getVoltageCEO();
        $piS = 0.45 * exp(3.1 * $vs);

        $lambda = $base * $piT * $piA * $piR * $piS * $piQ * $piE * pow(10,-6);

        return $lambda;
    }


}