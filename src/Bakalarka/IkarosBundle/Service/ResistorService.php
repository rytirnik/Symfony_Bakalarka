<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\Resistor;


class ResistorService {
	
	protected $doctrine;
    protected $systemService;
    protected $pcbService;

    public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
        $this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
    }
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:Resistor');
	}

//====================================================================================================================
	public function getItems() {
		return $this->getRepository()->findAll();
	}
	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

//====================================================================================================================
    public function getResQuality ($quality) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT m.*
                        FROM Resistor_quality m
                        WHERE m.Value = :m');
        $stmt->execute(array('m' => $quality));
        $qualR = $stmt->fetchAll();
        return $qualR[0]['Description'];
    }

//====================================================================================================================
    public function getResQualityAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Resistor_quality');
        $stmt->execute();
        $qualityAll = $stmt->fetchAll();

        foreach($qualityAll as $q) {
            $QualityChoices[$q['Value']] = $q['Description'];
        }
        return $QualityChoices;
    }

//====================================================================================================================
    public function getResMaterialAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Resistor_material');
        $stmt->execute();
        $materialAll = $stmt->fetchAll();

        foreach($materialAll as $m) {
            $MatChoices[$m['ResShortcut']] = $m['ResShortcut'];
        }
        return $MatChoices;
    }

//====================================================================================================================
    public function getResMaterialDescAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Resistor_material');
        $stmt->execute();
        $materialAll = $stmt->fetchAll();

        return $materialAll;
    }
//====================================================================================================================
    public function getResMaterialByShortcut($mat) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT m.*
                       FROM Resistor_material m
                       WHERE m.ResShortcut = :m');
        $stmt->execute(array('m' => $mat));
        return $stmt->fetch();
    }
//====================================================================================================================
    public function getActiveResistors($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, resQ.*
                        FROM
                       (SELECT res.*, q.Description
                       FROM Resistor res LEFT JOIN Resistor_quality q ON (res.Quality = q.Value)) AS resQ
                       LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(resQ.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "rezistor"');
        $stmt->execute(array(':id' => $pcbID));
        $resistors = $stmt->fetchAll();
        return $resistors;
    }

//====================================================================================================================
    public function calculateLam (Resistor $res, $pcbID) {
        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $sysTemp = $system->getTemp();
        $res->setTemp($sysTemp + $res->getDPTemp() + $res->getPassiveTemp());

        $material = $this->getResMaterialByShortcut($res->getMaterial());

        $base = $material['Lamb'];
        $piT_tab = $material['FactorT'];
        $piS_tab = intval($material['FactorS']);

        $temp = $res->getTemp();
        $piT = exp(((-1 * $piT_tab) / (8.617 * 0.00001)) * (1 / ($temp + 273) - 1 / 298));

        $pDiss = $res->getDissipationPower();
        $s_pom = $pDiss / $res->getMaxPower();
        switch($piS_tab) {
            case 0:
                $piS = 1;
                break;
            case 1:
                $piS = 0.71 * exp(1.1 * $s_pom);
                break;
            case 2:
                $piS = 0.54 * exp(2.04 * $s_pom);
                break;
        }

        $piP = pow($pDiss, 0.39);
        $piQ = $res->getQuality();

        $sEnv = $res->getEnvironment();

        $piE = $this->systemService->getPiE(91, $sEnv);

        $lambda = $base * $piT * $piP * $piS * $piQ * $piE * pow(10, -6);

        return $lambda;
    }



}