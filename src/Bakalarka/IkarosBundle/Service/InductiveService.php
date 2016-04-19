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

    public function getQualityCoilsChoices() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM QualityInductive');
        $stmt->execute();
        $QualityAll = $stmt->fetchAll();

        foreach($QualityAll as $q) {
            $QualityChoices[$q['Description']] = $q['Description'];
        }
        return $QualityChoices;
    }

//====================================================================================================================
    public function getQualityCoilsValue ($qualityDesc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT qual.Value
                        FROM QualityInductive qual
                        WHERE qual.Description = :desc');
        $stmt->execute(array(':desc' => $qualityDesc));
        $quality = $stmt->fetch();
        return $quality['Value'];
    }

//====================================================================================================================
    public function getTransformersDescChoices() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM InductiveTransformerTypes');
        $stmt->execute();
        $typesAll = $stmt->fetchAll();

        foreach($typesAll as $type) {
            $descChoices[$type['Description']] = $type['Description'];
        }
        return $descChoices;
    }

//====================================================================================================================
    public function getTransformersDescOptions() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM InductiveTransformerTypes');
        $stmt->execute();
        $typesAll = $stmt->fetchAll();

        $options = "";
        foreach($typesAll as $type) {
            $options = $options."<option value='".$type['Description']."'>".$type['Description']."</option>";
        }

        return $options;
    }

//====================================================================================================================
    public function getCoilsDescChoices() {
        $descChoices["Fixed Inductor or Choke"] = "Fixed Inductor or Choke";
        $descChoices["Variable Inductor"] = "Variable Inductor";
        $descChoices["Worst case"] = "Worst case";

        return $descChoices;
    }

//====================================================================================================================
    public function getCoilsDescOptions() {
        $options = "";

        $options = $options."<option value='Fixed Inductor or Choke'> Fixed Inductor or Choke </option>";
        $options = $options."<option value='Variable Inductor'> Variable Inductor </option>";
        $options = $options."<option value='Worst case'> Worst case </option>";

        return $options;
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
        //$piT = exp(-1925 * (1/($temp + 273) - 1/298));

        $type = $inductive->getDevType();
        $qual = $inductive->getQuality();
        if($type == "Coils") {
            $piQ = $this->getQualityCoilsValue($qual);
        }
        else {
            if($qual == "MIL-SPEC")
                $piQ = 1;
            else
                $piQ = 3;
        }

        //$lambda = $base * $piT * $piA * $piQ * $piE * pow(10,-6);

        $lambda = $piE * pow(10,-6);

        return $lambda;
    }


}