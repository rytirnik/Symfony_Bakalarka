<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\System;
use Bakalarka\IkarosBundle\Entity\Part;
use Bakalarka\IkarosBundle\Entity\PCB;
use Bakalarka\IkarosBundle\Entity\PartSMT;

class PcbService {
	
	protected $doctrine;
    protected $systemService;
    protected $partService;
	
	public function __construct(Registry $doctrine, SystemService $systemService) {
		$this->doctrine = $doctrine;
        $this->systemService = $systemService;

	}
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:PCB');
	}
	
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

    public function getSmtByID ($smtID){
        $RU = $this->doctrine->getManager()->getRepository('BakalarkaIkarosBundle:PartSMT');
        return $RU->findOneBy(array('ID_Part_SMT' => $smtID));
    }

    public function getActiveSmt($pcbID) {
        $RU = $this->doctrine->getManager()->getRepository('BakalarkaIkarosBundle:PartSMT');
        return $RU->findBy(array('PCB_ID' => $pcbID, 'DeleteDate' => NULL));
    }

    public function getActivePcbBySystemID($systemID) {
        $RU = $this->doctrine->getManager()->getRepository('BakalarkaIkarosBundle:PCB');
        return $RU->findBy(array('SystemID' => $systemID, 'DeleteDate' => NULL));
    }

    public function getActivePcbBySystemID_extended($systemID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM PCB pcb
                        LEFT JOIN (
                            SELECT pcb.ID_PCB AS id, COUNT( part.PCB_ID ) AS PartsCnt
                            FROM PCB pcb
                            JOIN Part part ON ( pcb.ID_PCB = part.PCB_ID )
                            WHERE pcb.SystemID = :sysID AND part.DeleteDate IS NULL
                            GROUP BY part.PCB_ID) AS neco ON ( neco.id = pcb.ID_PCB )
                        WHERE pcb.SystemID = :sysID AND pcb.DeleteDate IS NULL');
        $stmt->execute(array(':sysID' => $systemID));
        return $stmt->fetchAll();
    }

    //TODO ???
    public function getAllPcbBySystemID($systemID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM PCB pcb
                        LEFT JOIN (
                            SELECT pcb.ID_PCB AS id, COUNT( part.PCB_ID ) AS PartsCnt
                            FROM PCB pcb
                            JOIN Part part ON ( pcb.ID_PCB = part.PCB_ID )
                            WHERE pcb.SystemID = :sysID AND part.DeleteDate IS NULL
                            GROUP BY part.PCB_ID) AS neco ON ( neco.id = pcb.ID_PCB )
                        WHERE pcb.SystemID = :sysID AND pcb.DeleteDate IS NULL');
        $stmt->execute(array(':sysID' => $systemID));
        return $stmt->fetchAll();
    }

    public function getPartsSmtBySystemID ($systemID) {
        $query = $this->doctrine->getManager()
            ->createQuery('SELECT p FROM BakalarkaIkarosBundle:PartSMT p JOIN p.PCB_ID pcb
                                    WHERE pcb.SystemID = :id AND pcb.DeleteDate IS NULL AND p.DeleteDate IS NULL');
        $query->setParameters(array('id' => $systemID));
        return $query->getResult();
    }

    public function getActivePartsSmtByPcbID ($pcbID) {
        $RU = $this->doctrine->getManager()->getRepository('BakalarkaIkarosBundle:PartSMT');
        return $RU->findBy(array('PCB_ID' => $pcbID, 'DeleteDate' => NULL));
    }

    public function setDeleteDateToPcbs($pcbs) {
        $manager = $this->doctrine->getManager();
        foreach($pcbs as $pcb) {
            try {
                $pcb->setDeleteDate(new \DateTime());
                $manager->persist($pcb);
            } catch (\Exception $e) {
                $error = "Desku " + $pcb->getLabel() + " se nepodařilo vymazat.";
                return $error;
            }
        }
        return "";
    }

    public function setDeleteDateToPcb($pcb, $system) {
        $manager = $this->doctrine->getManager();
        try {
            $pcb->setDeleteDate(new \DateTime());
            $manager->persist($pcb);
            $manager->persist($system);
        } catch (\Exception $e) {
            $error = "Desku " + $pcb->getLabel() + " se nepodařilo vymazat.";
            return $error;
        }
        return "";
    }

    public function getEnvironmentPcb () {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM Environment e
                        WHERE e.ID_Section = 161 OR e.ID_Section = 162 ORDER BY e.ID_Section ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getEquipmentTypes() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM EquipmentType');
        $stmt->execute();
        $eqs = $stmt->fetchAll();

        foreach($eqs as $eq) {
            $EquipChoices[$eq['ID_EquipType']] = $eq['Description'];
        }
        return $EquipChoices;
    }

    public function getMaterials() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM SubstrateMaterial ORDER BY Description');
        $stmt->execute();
        $mat = $stmt->fetchAll();

        foreach($mat as $m) {
            $MatChoices[$m['ID_SubstrateMat']] = $m['Description'];
        }
        return $MatChoices;
    }

    public function getEquipmentTypeByID ($equipID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM EquipmentType e
                        WHERE e.ID_EquipType = :id');
        $stmt->execute(array(':id' => $equipID));
        return $stmt->fetchAll();
    }

    public function getEquipmentTypeByDesc ($desc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM EquipmentType e
                        WHERE e.Description = :d');
        $stmt->execute(array(':d' => $desc));
        return $stmt->fetchAll();
    }

    public function getMaterialByID($matID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT m.*
                        FROM SubstrateMaterial m
                        WHERE m.ID_SubstrateMat = :id');
        $stmt->execute(array(':id' => $matID));
        return $stmt->fetchAll();
    }

    public function getMaterialByDesc($desc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT m.*
                        FROM SubstrateMaterial m
                        WHERE m.Description = :mat');
        $stmt->execute(array(':mat' => $desc));
        return $stmt->fetchAll();
    }

    public function lamPCBwire (PCB $pcb, $piE) {
        $b = 0.000017;
        $layers = $pcb->getLayers();
        $piQ = $pcb->getQuality();
        $pAuto = $pcb->getSolderingPointAuto();
        $pHand = $pcb->getSolderingPointHand();

        //$piE = 1; //DOSTAT ZE SYSTEMU
        $piC = 1;
        if($layers > 2)
            $piC = 0.65 * pow($layers, 0.63);
        $lambda = $b * (($pAuto * $piC) + ($pHand * ($piC + 13))) * $piQ * $piE * pow(10, -6);

        return $lambda;
    }

    public function lamPCBsmt (PartSMT $smtP, $CR, $alfaS, $zivot, $dt) {
        $piLC = $smtP->getLeadConfig();
        $h = 5;
        $delka = $smtP->getHeight();
        $sirka = $smtP->getWidth();
        $d = (sqrt(pow($delka,2) + pow($sirka,2))) / 2;

        $alfaCC = $smtP->getTCEPackage();
        $otepleni = $smtP->getTempDissipation();
        $y = Abs(($alfaS * $dt) - ($alfaCC * ($dt + $otepleni))) * pow(10, -6);
        $nf = $piLC * 3.5 * pow(($d / (0.65 * $h) * $y), -2.26);
        $asmt = $nf / $CR;

        $x = ($zivot * 8760) / $asmt;

        if ($x <= 0.1) $ecf = 0.13;
        else if ($x > 0.1 && $x <= 0.2) $ecf = 0.15;
        else if ($x > 0.2 && $x <= 0.3) $ecf = 0.23;
        else if ($x > 0.3 && $x <= 0.4) $ecf = 0.31;
        else if ($x > 0.4 && $x <= 0.5) $ecf = 0.41;
        else if ($x > 0.5 && $x <= 0.6) $ecf = 0.51;
        else if ($x > 0.6 && $x <= 0.7) $ecf = 0.61;
        else if ($x > 0.7 && $x <= 0.8) $ecf = 0.68;
        else if ($x > 0.8 && $x <= 0.9) $ecf = 0.76;
        else $ecf = 1;

        $LamSMT = $ecf / $asmt;
        $LamSMT *= $smtP->getCnt();
        return $LamSMT;
    }
}