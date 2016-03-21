<?php

namespace Bakalarka\IkarosBundle\Service;

use Bakalarka\IkarosBundle\Entity\Optoelectronics;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\DiodeLF;


class OptoService {
	
	protected $doctrine;
	
	public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
		$this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
	}

	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:Optoelectronics');
	}

//====================================================================================================================
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	public function getItem($id) {
		return $this->getRepository()->find($id);
	}
//====================================================================================================================

    public function getOptoQualityChoices() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM QualityDiodeopto');
        $stmt->execute();
        $QualityAll = $stmt->fetchAll();

        foreach($QualityAll as $q) {
            $QualityChoices[$q['Description']] = $q['Description'];
        }
        return $QualityChoices;
    }

//====================================================================================================================

    public function getOptoAppChoices() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM optoApplication');
        $stmt->execute();
        $diodeAppAll = $stmt->fetchAll();

        foreach($diodeAppAll as $q) {
            $AppChoices[$q['Description']] = $q['Description'];
        }
        return $AppChoices;
    }

//====================================================================================================================
    public function getActiveOptos ($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT neco.*, opto.*
                        FROM Optoelectronics opto JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON pcb.ID_PCB=part.PCB_ID
                        WHERE pcb.ID_PCB = :id AND pcb.DeleteDate IS NULL AND part.DeleteDate IS NULL) AS neco
                        ON opto.ID_Part = neco.ID_Part');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }


//====================================================================================================================
    public function getApplicationValue ($appDesc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT app.Value
                        FROM optoApplication app
                        WHERE app.Description = :desc');
        $stmt->execute(array(':desc' => $appDesc));
        $app = $stmt->fetch();

        return $app['Value'];
    }

//====================================================================================================================
    public function getQualityValue ($qualityDesc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT qual.Value
                        FROM QualityDiodeopto qual
                        WHERE qual.Description = :desc');
        $stmt->execute(array(':desc' => $qualityDesc));
        $quality = $stmt->fetch();
        return $quality['Value'];
    }

//====================================================================================================================
    public function calculateLam (Optoelectronics $opto, $pcbID) {
        $sEnv = $opto->getEnvironment();
        /*$stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                       FROM Environment e
                       WHERE e.ID_Section = 611');
        $stmt->execute();
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];*/

        $piE = $this->systemService->getPiE(611, $sEnv);

        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $temp = $opto->getPassiveTemp() + $opto->getDPTemp() + $system->getTemp();
        $opto->setTemp($temp);

        $piT = exp(-2790 * (1 / ($temp + 273) - 1 / 298));
        $base = $this->getApplicationValue($opto->getApplication());
        $piQ = $this->getQualityValue($opto->getQuality());


        $lambda = $base * $piT * $piQ * $piE * pow(10,-6);

        return $lambda;
    }


}