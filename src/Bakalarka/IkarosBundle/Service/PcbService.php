<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\System;
use Bakalarka\IkarosBundle\Entity\Part;
use Bakalarka\IkarosBundle\Entity\PCB;


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
}