<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\Capacitor;


class CapacitorService {
	
	protected $doctrine;
    protected $systemService;
    protected $pcbService;

    public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
        $this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
    }
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:Capacitor');
	}

//====================================================================================================================
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

//====================================================================================================================
    public function getCapQuality ($quality) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT m.*
                        FROM QualityCapacitor m
                        WHERE m.Value = :m');
        $stmt->execute(array('m' => $quality));
        $qualC = $stmt->fetchAll();
        return $qualC[0]['Description'];
    }
//====================================================================================================================
    public function getCapQualityAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM QualityCapacitor');
        $stmt->execute();
        $capQualityAll = $stmt->fetchAll();

        foreach($capQualityAll as $q) {
            $QualityChoicesC[$q['Value']] = $q['Description'];
        }
        return $QualityChoicesC;
    }

//====================================================================================================================
    public function getCapMaterialAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM MaterialCapacitor');
        $stmt->execute();
        $capMaterialAll = $stmt->fetchAll();
        foreach($capMaterialAll as $m) {
            $MatChoicesC[$m['CapShortcut']] = $m['CapShortcut'];
        }
        return $MatChoicesC;
    }

//====================================================================================================================
    public function getCapMaterialDescAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM MaterialCapacitor');
        $stmt->execute();
        $capMaterialAll = $stmt->fetchAll();

        return $capMaterialAll;
    }

//====================================================================================================================
    public function getActiveCapacitors($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, capQ.*
                        FROM
                       (SELECT cap.*, q.Description
                       FROM Capacitor cap LEFT JOIN QualityCapacitor q ON (cap.Quality = q.Value)) AS capQ
                       LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(capQ.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "kondenzátor"');
        $stmt->execute(array(':id' => $pcbID));
        $capacitors = $stmt->fetchAll();
        return $capacitors;
    }

//====================================================================================================================
    public function calculateLam ($cap, $pcbID) {
        //$cap = $this->getItem($partID);
        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $sysTemp = $system->getTemp();
        $cap->setTemp($sysTemp + $cap->getPassiveTemp());

        $matC = $cap->getMaterial();
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT m.*
                        FROM MaterialCapacitor m
                        WHERE m.CapShortcut = :m');
        $stmt->execute(array('m' => $matC));
        $materialC = $stmt->fetchAll();

        $base = $materialC[0]['Lamb'];
        $facT = floatval($materialC[0]['FactorT']);
        $facC = floatval($materialC[0]['FactorC']);
        $facV = $materialC[0]['FactorV'];
        $facRS = $materialC[0]['PiSR'];
        $piQ = $cap->getQuality();

        if (!$facRS) {
            $peek = $cap->getVoltageOperational();
            $ser = $cap->getSerialResistor();
            If ($peek <= 0) $peek = 0.001;
            $s_pom = $ser / $peek;

            if ($s_pom > 0.8) $pi_sr = 0.66;
            else if ($s_pom > 0.6 && $s_pom <= 0.8)  $pi_sr = 1;
            else if ($s_pom > 0.4 && $s_pom <= 0.6 ) $pi_sr = 1.3;
            else if ($s_pom > 0.2 && $s_pom <= 0.4)  $pi_sr = 2;
            else if ($s_pom > 0.1 && $s_pom <= 0.2)  $pi_sr = 2.7;
            else  $pi_sr = 3.3;
        }
        else
            $pi_sr = 1;

        $teplota = $cap->getTemp();
        $piT = exp((-$facT / (8.617 * 0.00001)) * (1 / ($teplota + 273) - 1 / 298));

        $hodnota = $cap->getValue();
        $piC = pow($hodnota, $facC);

        $max = $cap->getVoltageMax();
        $oper = $cap->getVoltageOperational();
        $s = $oper/$max;

        switch($facV) {
            case 1:
                $piV = pow(($s/0.6), 5) + 1;
                break;
            case 2:
                $piV = pow(($s/0.6), 10) + 1;
                break;
            case 3:
                $piV = pow(($s/0.6), 3) + 1;
                break;
            case 4:
                $piV = pow(($s/0.6), 17) + 1;
                break;
            case 5:
                $piV = pow(($s/0.5), 3) + 1;
                break;
        }

        $sEnv = $cap->getEnvironment();
        /*$stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM Environment e
                        WHERE e.ID_Section = 101');
        $stmt->execute();
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];*/
        $piE = $this->systemService->getPiE(101, $sEnv);

        $lambda = $base * $piT * $piC * $piV * $pi_sr * $piQ * $piE * pow(10, -6);

        return $lambda;
    }



//====================================================================================================================
    /*public function setLams(Capacitor $cap, $pcbID) {
        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $sysTemp = $system->getTemp();
        $cap->setTemp($sysTemp + $cap->getPassiveTemp());

        $lambda = $this->lamCapacitor($cap);

        $cap->setLam($lambda);
        $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda);
        $system->setLam($system->getLam() + $lambda);

        $cap->setPCBID($pcb);

        try {
            $em = $this->doctrine->getManager();
            $em->persist($cap);
            $em->persist($pcb);
            $em->persist($system);
            $em->flush();

        } catch (\Exception $e) {
            return $e;
        }
        return "";
    }*/

}