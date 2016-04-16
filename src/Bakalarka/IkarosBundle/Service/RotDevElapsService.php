<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\RotDevElaps;


class RotDevElapsService {
	
	protected $doctrine;
	
	public function __construct(Registry $doctrine, $systemService) {
		$this->doctrine = $doctrine;
        $this->systemService = $systemService;
	}
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:RotDevElaps');
	}

//====================================================================================================================
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

//====================================================================================================================
    public function getActiveRotDevElaps($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, rot.*
                       FROM RotDevElaps rot LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(rot.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "měřič motohodin"');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function calculateLam (RotDevElaps $rotElaps, $pcbID = -1) {
        $sEnv = $rotElaps->getEnvironment();
        $piE = $this->systemService->getPiE(123, $sEnv);

        $type = $rotElaps->getDevType();
        if($type == "A.C.")
            $base = 20;
        else if ($type == "Inverter Driven")
            $base = 30;
        else
            $base = 80;

        $temp = $rotElaps->getTempOperational() / $rotElaps->getTempMax();

        if($temp <= 0.5)
            $piT = 0.5;
        else if ($temp > 0.5 && $temp <= 0.6)
            $piT = 0.6;
        else if ($temp > 0.6 && $temp <= 0.8)
            $piT = 0.8;
        else
            $piT = 1;

        $lambda = $base * $piT * $piE * pow(10, -6);
        return $lambda;
    }


}