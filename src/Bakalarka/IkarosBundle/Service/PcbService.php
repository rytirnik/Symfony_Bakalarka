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



}