<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\Fuse;


class FuseService {
	
	protected $doctrine;


	
	public function __construct(Registry $doctrine) {
		$this->doctrine = $doctrine;

	}
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:Fuse');
	}
	
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

    public function getActiveFuses($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, fuse.*
                       FROM Fuse fuse LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(fuse.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "pojistka"');

        $stmt->execute(array(':id' => $pcbID));
        $fuses = $stmt->fetchAll();
        return $fuses;
    }

    public function lamFuse (Fuse $fuse) {
        $sEnv = $fuse->getEnvironment();
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM Environment e
                        WHERE e.ID_Section = 221');
        $stmt->execute();
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];

        $base = 0.01;
        $lambda = $base * $piE * pow(10, -6);

        return $lambda;
    }


}