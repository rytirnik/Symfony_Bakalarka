<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\System;
use Bakalarka\IkarosBundle\Entity\Part;
use Bakalarka\IkarosBundle\Entity\PCB;


class PartService {
	
	protected $doctrine;
    protected $systemService;
    protected $pcbService;
	
	public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
		$this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
	}
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:Part');
	}

//====================================================================================================================
	public function getItems() {
		return $this->getRepository()->findAll();
	}
	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

//====================================================================================================================
    //vraci entity
    public function getActiveParts($systemID) {
        $query = $this->doctrine->getManager()
            ->createQuery('SELECT p FROM BakalarkaIkarosBundle:Part p JOIN p.PCB_ID pcb
                                    WHERE pcb.SystemID = :id AND pcb.DeleteDate IS NULL AND p.DeleteDate IS NULL');
        $query->setParameters(array('id' => $systemID));
        return $query->getResult();
    }

//====================================================================================================================
    //vraci pole
    public function getActivePartsBySystemID ($systemID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT p.*
                        FROM Part p JOIN PCB pcb ON (p.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.SystemID = :sysID AND p.DeleteDate IS NULL AND pcb.DeleteDate IS NULL
                        ORDER BY p.entity_type');

        $stmt->execute(array(':sysID' => $systemID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function getAllPartsBySystemID ($systemID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT p.*
                        FROM Part p JOIN PCB pcb ON (p.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.SystemID = :sysID
                        ORDER BY p.entity_type');
        $stmt->execute(array(':sysID' => $systemID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function getActivePartsByPcbID ($pcbID) {
        $RU = $this->doctrine->getManager()->getRepository('BakalarkaIkarosBundle:Part');
        return $RU->findBy(array('PCB_ID' => $pcbID, 'DeleteDate' => NULL));
    }

//====================================================================================================================
    public function getPartsAddictedOnSysTemp ($systemID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT part.ID_Part, part.entity_type
                        FROM Part part JOIN
                        (SELECT pcb.ID_PCB
                        FROM System system JOIN PCB pcb ON (system.ID_System = pcb.SystemID)
                        WHERE system.ID_System = :sysID) AS desky
                        ON (desky.ID_PCB = part.PCB_ID)
                        WHERE part.DeleteDate IS NULL AND Temp IS NOT NULL');
        $stmt->execute(array(':sysID' => $systemID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function setDeleteDateToParts($parts) {
        $manager = $this->doctrine->getManager();
        foreach($parts as $part) {
            try {
                $part->setDeleteDate(new \DateTime());
                $manager->persist($part);
            } catch (\Exception $e) {
                $error = "Součástku " + $part->getLabel() + " se nepodařilo vymazat.";
                return $error;
            }
        }
        return "";
    }

//====================================================================================================================
   public function setLams($lambda, Part $part, $pcbID = -1, $oldLam = 0) {

       if($pcbID == -1)
           $pcb = $this->pcbService->getItem($part->getPCBID());
       else {
            $pcb = $this->pcbService->getItem($pcbID);
            $part->setPCBID($pcb);
       }
       $system = $this->systemService->getItem($pcb->getSystemID());

       $part->setLam($lambda);
       $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda - $oldLam);
       $system->setLam($system->getLam() + $lambda - $oldLam);

       try {
           $this->doctrine->getManager()->persist($part);
           $this->doctrine->getManager()->persist($pcb);
           $this->doctrine->getManager()->persist($system);
           $this->doctrine->getManager()->flush();

       } catch (\Exception $e) {
            return $e;
       }

       return "";
   }

//====================================================================================================================
    public function getType($id) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT part.entity_type
                       FROM Part part
                       WHERE part.ID_Part = :id');

        $stmt->execute(array(':id' => $id));
        $type = $stmt->fetchAll();
        return $type[0]["entity_type"];
    }

//====================================================================================================================
    public function subtractLam ($id) {
        $part = $this->getItem($id);
        $lam = $part->getLam();

        $pcb = $this->pcbService->getItem($part->getPCBID());
        $pcb->setSumPartsLam($pcb->getSumPartsLam() - $lam);

        $system = $this->systemService->getItem($pcb->getSystemID());
        $system->setLam($system->getLam() - $lam);

        $part->setDeleteDate(new \DateTime());

        try {
            $em = $this->doctrine->getManager();
            $em->persist($part);
            $em->persist($pcb);
            $em->persist($system);
            $em->flush();
        } catch (\Exception $e) {
            $error = "Součástku " + $part->getLabel() + " se nepodařilo vymazat.";
            return $error;
        }
        return "lam_".$lam;
    }



}