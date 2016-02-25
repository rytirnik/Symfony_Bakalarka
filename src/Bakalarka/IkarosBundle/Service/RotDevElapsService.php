<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\RotDevElaps;


class RotDevElapsService {
	
	protected $doctrine;
	
	public function __construct(Registry $doctrine) {
		$this->doctrine = $doctrine;
	}
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:RotDevElaps');
	}
	
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	public function getItem($id) {
		return $this->getRepository()->find($id);
	}


    public function lamRotElaps (RotDevElaps $rotElaps) {
        $sEnv = $rotElaps->getEnvironment();
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                       FROM Environment e
                       WHERE e.ID_Section = 123');
        $stmt->execute();
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];

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