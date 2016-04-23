<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\InductiveDev;


class InductiveService {

    protected $doctrine;

    public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
        $this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
    }

    protected function getRepository() {
        return $this->doctrine->getRepository('BakalarkaIkarosBundle:InductiveDev');
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
    public function getQualityTransChoices($options = 0) {
        if(!$options)
            $qualityChoices = array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower");
        else {
            $qualityChoices = "";
            $qualityChoices = $qualityChoices."<option value='MIL-SPEC'> MIL-SPEC </option>";
            $qualityChoices = $qualityChoices."<option value='Lower'> Lower </option>";
        }
        return $qualityChoices;
    }
//====================================================================================================================

    public function getQualityCoilsChoices($options = 0) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Inductive_quality');
        $stmt->execute();
        $QualityAll = $stmt->fetchAll();

        if(!$options) {
            foreach ($QualityAll as $q) {
                $QualityChoices[$q['Description']] = $q['Description'];
            }
        }
        else {
            $QualityChoices = "";
            foreach($QualityAll as $q) {
                $QualityChoices = $QualityChoices."<option value='".$q['Description']."'>".$q['Description']."</option>";
            }
        }
        return $QualityChoices;
    }

//====================================================================================================================
    public function getTransformersDescChoices($option = 0) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Inductive_Type
                        WHERE DevType = "Transformers"');
        //$stmt->execute(array(':type' => "Transformers"));
        $stmt->execute();
        $typesAll = $stmt->fetchAll();

        if(!$option) {
            foreach ($typesAll as $type) {
                $descChoices[$type['Description']] = $type['Description'];
            }
        }
        else {
            $descChoices = "";
            foreach($typesAll as $type) {
                $descChoices = $descChoices."<option value='".$type['Description']."'>".$type['Description']."</option>";
            }
        }

        return $descChoices;
    }

//====================================================================================================================
    public function getCoilsDescChoices($options = 0) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Inductive_Type
                        WHERE DevType = "Coils"');
        //$stmt->execute(array(':type' => "Transformers"));
        $stmt->execute();
        $typesAll = $stmt->fetchAll();

        if(!$options) {
            foreach ($typesAll as $type) {
                $descChoices[$type['Description']] = $type['Description'];
            }
        }
        else {
            $descChoices = "";
            foreach($typesAll as $type) {
                $descChoices = $descChoices."<option value='".$type['Description']."'>".$type['Description']."</option>";
            }
        }

        return $descChoices;
    }


//====================================================================================================================
    public function getQualityValue ($qualityDesc) {    //stejne pro oba typy
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT qual.Value
                        FROM Inductive_quality qual
                        WHERE qual.Description = :desc');
        $stmt->execute(array(':desc' => $qualityDesc));
        $quality = $stmt->fetch();
        return $quality['Value'];
    }
//====================================================================================================================
    public function getDescValue ($devType, $desc) {    //pro oba typy
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT itype.Value
                        FROM Inductive_Type itype
                        WHERE itype.Description = :desc AND itype.DevType = :type');
        $stmt->execute(array(':desc' => $desc, ':type' => $devType));
        $result = $stmt->fetch();
        return $result['Value'];
    }

//====================================================================================================================
    public function getActiveInductives ($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT neco.*, inductive.*
                        FROM InductiveDev inductive JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON pcb.ID_PCB=part.PCB_ID
                        WHERE pcb.ID_PCB = :id AND pcb.DeleteDate IS NULL AND part.DeleteDate IS NULL) AS neco
                        ON inductive.ID_Part = neco.ID_Part');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function calculateLam (InductiveDev $inductive, $pcbID) {
        $sEnv = $inductive->getEnvironment();
        $piE = $this->systemService->getPiE(111, $sEnv);

        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $temp = $inductive->getTempPassive() + $inductive->getTempDissipation() + $system->getTemp();
        $inductive->setTemp($temp);

        $surface = $inductive->getSurface();
        $weight = $inductive->getWeight();
        $powerLoss = $inductive->getPowerLoss();
        if($surface)
            $deltaT = 125 * $powerLoss / ($surface / 6.452);    //prevod na palce
        else if ($weight)
            $deltaT = 11.5 * $powerLoss / pow($weight * 2.2046, 0.6766); //prevod na lbs
        else
            $deltaT = 35;
        $ths = $temp + 1.1 * $deltaT;

        $piT = exp((-0.11 / (8.617 * pow(10, -5))) * ((1 / ($ths + 273)) - 1 / 298));

        $type = $inductive->getDevType();
        $desc = $inductive->getDescription();
        $base = $this->getDescValue($type, $desc);

        $qual = $inductive->getQuality();
        $piQ = $this->getQualityValue($qual);

        $lambda = $base * $piT * $piQ * $piE * pow(10,-6);

        return $lambda;
    }
}