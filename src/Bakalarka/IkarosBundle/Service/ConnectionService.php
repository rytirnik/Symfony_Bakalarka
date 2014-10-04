<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\Connections;


class ConnectionService {
	
	protected $doctrine;

	
	public function __construct(Registry $doctrine) {
		$this->doctrine = $doctrine;
	}
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:Connnections');
	}
	
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

    public function getConType($conType) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM ConnectionType c
                        WHERE c.Lamb = :lamb');
        $stmt->execute(array('lamb' => $conType));
        $conType = $stmt->fetchAll();
        return $conType[0]['Description'];
    }

    public function getConTypeAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM ConnectionType');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function lamConnection (Connections $con) {
        $sEnv = $con->getEnvironment();
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM Environment e
                        WHERE e.ID_Section = 171');
        $stmt->execute();
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];

        $base = $con->getConnectionType();
        $lambda = $base * $piE * pow(10, -6);

        return $lambda;
    }


}