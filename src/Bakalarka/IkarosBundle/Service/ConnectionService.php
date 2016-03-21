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
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:Connections');
	}

//====================================================================================================================
	public function getItems() {
		return $this->getRepository()->findAll();
	}
	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

//====================================================================================================================
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

//====================================================================================================================
    public function getConTypeAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM ConnectionType');
        $stmt->execute();
        $conTypes = $stmt->fetchAll();

        foreach($conTypes as $m) {
            $conTypeChoices[$m['Lamb']] = $m['Description'];
        }
        return $conTypeChoices;
    }

//====================================================================================================================
    public function getActiveConnections($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, conQ.*
                        FROM
                       (SELECT con.*, q.Description
                       FROM Connections con LEFT JOIN ConnectionType q ON (con.ConnectionType = q.Lamb)) AS conQ
                       LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(conQ.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "propojení"');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function calculateLam (Connections $con, $pcbID = -1) {
        $sEnv = $con->getEnvironment();
        /*$stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM Environment e
                        WHERE e.ID_Section = 171');
        $stmt->execute();
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];*/
        $piE = $this->systemService->getPiE(171, $sEnv);

        $base = $con->getConnectionType();
        $lambda = $base * $piE * pow(10, -6);

        return $lambda;
    }


}