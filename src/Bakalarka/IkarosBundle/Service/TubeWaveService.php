<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\TubeWave;


class TubeWaveService {
	
	protected $doctrine;
	
	public function __construct(Registry $doctrine) {
		$this->doctrine = $doctrine;
	}
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:TubeWave');
	}
	
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}


    public function lamTubeWave (TubeWave $tubeWave) {
        $sEnv = $tubeWave->getEnvironment();
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                       FROM Environment e
                       WHERE e.ID_Section = 72');
        $stmt->execute();
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];

        $p = $tubeWave->getPower();
        $f = $tubeWave->getFrequency();

        $base = 11 * pow(1.00001, $p) * pow(1.1, $f) * pow(10, -6);

        $lambda = $base * $piE;
        return $lambda;
    }


}