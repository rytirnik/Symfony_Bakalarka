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


   public function setLams($lambda, Part $part, $pcbID) {

       $pcb = $this->pcbService->getItem($pcbID);
       $system = $this->systemService->getItem($pcb->getSystemID());

       $part->setLam($lambda);
       $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda);
       $system->setLam($system->getLam() + $lambda);

       $part->setPCBID($pcb);

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


}