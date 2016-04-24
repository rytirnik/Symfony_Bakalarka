<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\DiodeRF;


class DiodeRFService {

    protected $doctrine;

    public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
        $this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
    }

    protected function getRepository() {
        return $this->doctrine->getRepository('BakalarkaIkarosBundle:DiodeRF');
    }

//====================================================================================================================
    public function getItems() {
        return $this->getRepository()->findAll();
    }

    public function getItem($id) {
        return $this->getRepository()->find($id);
    }

//====================================================================================================================
    public function getActiveDiodesRF ($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT neco.*, diode.*
                        FROM DiodeRF diode JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON pcb.ID_PCB=part.PCB_ID
                        WHERE pcb.ID_PCB = :id AND pcb.DeleteDate IS NULL AND part.DeleteDate IS NULL) AS neco
                        ON diode.ID_Part = neco.ID_Part');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

//====================================================================================================================

    public function getQualityChoices($diodeType = "") {
        if($diodeType == "Schottky")
            $type = 1;
        else
            $type = 2;
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM DiodeRF_quality
                        WHERE diodeType = :diodeType');
        $stmt->execute(array(':diodeType' => $type));
        $diodeQualityAll = $stmt->fetchAll();

        foreach($diodeQualityAll as $q) {
            $QualityChoices[$q['Description']] = $q['Description'];
        }
        return $QualityChoices;
    }

//====================================================================================================================

    public function getTypeChoices() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM DiodeRF_type');
        $stmt->execute();
        $diodeTypeAll = $stmt->fetchAll();

        foreach($diodeTypeAll as $q) {
            $typeChoices[$q['Description']] = $q['Description'];
        }
        return $typeChoices;
    }

//====================================================================================================================
    public function getTypesAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM DiodeRF_type');
        $stmt->execute();
        $diodeTypeAll = $stmt->fetchAll();

        return $diodeTypeAll;
    }

//====================================================================================================================
    public function getDiodeTypeValue ($typeDesc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM DiodeRF_type t
                        WHERE t.Description = :desc');
        $stmt->execute(array(':desc' => $typeDesc));
        $type = $stmt->fetch();

        return $type['Value'];
    }

//====================================================================================================================
    public function getQualityValue ($qualityDesc, $diodeType) {
        if($diodeType == "Schottky")
            $type = 1;
        else
            $type = 2;
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM DiodeRF_quality qual
                        WHERE qual.Description = :desc AND qual.DiodeType = :diodeType');
        $stmt->execute(array(':desc' => $qualityDesc, ':diodeType' => $type));
        $diodeQuality = $stmt->fetch();
        return $diodeQuality['Value'];
    }

//====================================================================================================================
    public function getApplicationChoices () {
        $appChoices = array();
        $appChoices['Varactor, Voltage, Control'] = "Varactor, Voltage, Control";
        $appChoices['Varactor, Multiplier'] = "Varactor, Multiplier";
        $appChoices['All Other Diodes'] = "All Other Diodes";
        $appChoices['Worst case'] = "Worst case";

        return $appChoices;
    }
//====================================================================================================================
    public function getApplicationValue ($appDesc) {
        if($appDesc == "Varactor, Voltage, Control")
            return 0.5;
        else if($appDesc == "All Other Diodes")
            return 1;
        else
            return 2.5;
    }
//====================================================================================================================
    public function calculateLam (DiodeRF $diode, $pcbID) {
        $sEnv = $diode->getEnvironment();
        $piE = $this->systemService->getPiE(62, $sEnv);

        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $temp = $diode->getTempPassive() + $diode->getTempDissipation() + $system->getTemp();
        $diode->setTemp($temp);

        $diodeType = $diode->getDiodeType();
        $base = $this->getDiodeTypeValue($diodeType);
        $piQ = $this->getQualityValue($diode->getQuality(), $diodeType);

        $power = $diode->getPowerRated();
        if($diodeType == "PIN") {
            if($power <= 0)
                $piR = 2.4;
            else if($power <= 10)
                $piR = 0.5;
            else
                $piR = 0.326 * log($power) - 0.25;
        }
        else
            $piR = 1;

        if($diodeType == "IMPATT")
            $piT = exp(-5260 * (1/($temp + 273) - 1/298));
        else
            $piT = exp(-2100 * (1/($temp + 273) - 1/298));

        $piA = $this->getApplicationValue($diode->getApplication());

        $lambda = $base * $piT * $piA * $piR * $piQ * $piE * pow(10,-6);
        //$lambda = $piQ;

        return $lambda;
    }


}