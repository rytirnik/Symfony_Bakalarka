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
	
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

    public function getResQuality ($quality) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT m.*
                        FROM QualityResistor m
                        WHERE m.Value = :m');
        $stmt->execute(array('m' => $quality));
        $qualR = $stmt->fetchAll();
        return $qualR[0]['Description'];
    }

    public function getResQualityAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM QualityResistor');
        $stmt->execute();
        $qualityAll = $stmt->fetchAll();

        foreach($qualityAll as $q) {
            $QualityChoices[$q['Value']] = $q['Description'];
        }
        return $QualityChoices;
    }

    public function getResMaterialAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM MaterialResistor');
        $stmt->execute();
        $materialAll = $stmt->fetchAll();

        foreach($materialAll as $m) {
            $MatChoices[$m['ResShortcut']] = $m['ResShortcut'];
        }
        return $MatChoices;
    }

    public function getResMaterialDescAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM MaterialResistor');
        $stmt->execute();
        $materialAll = $stmt->fetchAll();

        return $materialAll;
    }

    public function lamResistor (Resistor $res) {
        $mat = $res->getMaterial();
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT m.*
                       FROM MaterialResistor m
                       WHERE m.ResShortcut = :m');
        $stmt->execute(array('m' => $mat));
        $material = $stmt->fetchAll();

        $base = $material[0]['Lamb'];
        $piT_tab = $material[0]['FactorT'];
        $piS_tab = intval($material[0]['FactorS']);

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
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                       FROM Environment e
                       WHERE e.ID_Section = 91');
        $stmt->execute();
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];

        $lambda = $base * $piT * $piP * $piS * $piQ * $piE * pow(10, -6);

        return $lambda;
    }

    public function setLams(Resistor $res, $pcbID) {
        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $sysTemp = $system->getTemp();
        $res->setTemp($sysTemp + $res->getDPTemp() + $res->getPassiveTemp());

        $lambda = $this->lamResistor($res);

        $res->setLam($lambda);
        $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda);
        $system->setLam($system->getLam() + $lambda);

        $res->setPCBID($pcb);

        try {
            $em = $this->doctrine->getManager();
            $em->persist($res);
            $em->persist($pcb);
            $em->persist($system);
            $em->flush();

        } catch (\Exception $e) {
            return $e;
        }
        return "";
    }


}