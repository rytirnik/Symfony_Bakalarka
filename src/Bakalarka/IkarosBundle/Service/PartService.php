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
	
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}


   public function setLams($lambda, Part $part, $oldLam = 0, $pcbID = -1) {

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