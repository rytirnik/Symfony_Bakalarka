<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\ConnectorGen;
use Bakalarka\IkarosBundle\Entity\ConnectorSoc;


class ConnectorService {
	protected $doctrine;
    protected $systemService;
    protected $pcbService;

    public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
        $this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
    }
	
	protected function getRepositoryGen() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:ConnectorGen');
	}

    protected function getRepositorySoc() {
        return $this->doctrine->getRepository('BakalarkaIkarosBundle:ConnectorSoc');
    }
	
	public function getItemsGen() {
		return $this->getRepositoryGen()->findAll();
	}

    public function getItemsSoc() {
        return $this->getRepositorySoc()->findAll();
    }

	
	public function getItemGen($id) {
		return $this->getRepositoryGen()->find($id);
	}

    public function getItemSoc($id) {
        return $this->getRepositorySoc()->find($id);
    }

    public function getConSocTypeAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM ConnectorSocType');
        $stmt->execute();
        $conSocTypes = $stmt->fetchAll();

        foreach($conSocTypes as $m) {
            $conSocTypeChoices[$m['Description']] = $m['Description'];
        }
        return $conSocTypeChoices;
    }

    public function getConGenTypeAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM ConnectorGenType');
        $stmt->execute();
        $conGenTypes = $stmt->fetchAll();

        foreach($conGenTypes as $m) {
            $conGenTypeChoices[$m['Description']] = $m['Description'];
        }
        return $conGenTypeChoices;
    }

    public function getActiveConnectorsSoc ($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, conS.*
                       FROM ConnectorSoc conS LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(conS.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "konektor, soket"');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

    public function getActiveConnectorsGen($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, conG.*
                       FROM ConnectorGen conG LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(conG.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "konektor, obecný"');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

    public function lamConSoc (ConnectorSoc $conSoc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM ConnectorSocType c
                        WHERE c.Description = :d');
        $stmt->execute(array('d' => $conSoc->getConnectorType()));
        $conType = $stmt->fetchAll();
        $lamB = floatval($conType[0]['Lamb']);

        $qual = $conSoc->getQuality();
        if ($qual == "MIL-SPEC")
            $piQ = 0.3;
        else
            $piQ = 1;

        $sEnv = $conSoc->getEnvironment();
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM Environment e
                        WHERE e.ID_Section = 152');
        $stmt->execute();
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];

        $q = 0.39;
        $n = $conSoc->getActivePins();
        $piP = exp(pow(($n-1)/10, $q));

        $lambda = $lamB * $piP * $piQ * $piE * pow(10, -6);

        return $lambda;
    }

    public function lamConGen (ConnectorGen $conGen) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM ConnectorGenType c
                        WHERE c.Description = :d');
        $stmt->execute(array('d' => $conGen->getConnectorType()));
        $conType = $stmt->fetchAll();
        $base = floatval($conType[0]['Lamb']);

        $prumerI = $conGen->getCurrentContact();
        $popis = $conGen->getConnectorType();
        $contactCnt = $conGen->getContactCnt();

        if ($popis == "RF Coaxial")
            $deltaT = 5;
        else if ($popis == "RF Coaxial (High Power)")
            $deltaT = 50;
        else {
            if ($contactCnt <= 12)
                $deltaT = 0.1 * ($prumerI) ^ 1.85;
            else if ($contactCnt >= 13 && $contactCnt <= 16)
                $deltaT = 0.274 * pow($prumerI, 1.85);
            else if ($contactCnt >=  17  && $contactCnt <=  18)
                $deltaT = 0.429 * pow($prumerI, 1.85);
            else if ($contactCnt >=  19  && $contactCnt <=  20)
                $deltaT = 0.64 * pow($prumerI, 1.85);
            else if ($contactCnt >=  21  && $contactCnt <=  22)
                $deltaT = 0.989 * pow($prumerI, 1.85);
            else if ($contactCnt >=  23  && $contactCnt <=  24)
                $deltaT = 1.345 * pow($prumerI, 1.85);
            else if ($contactCnt >=  25  && $contactCnt <=  28)
                $deltaT = 2.286 * pow($prumerI, 1.85);
            else if ($contactCnt >=  29  && $contactCnt <=  31)
                $deltaT = 2.856 * pow($prumerI, 1.85);
            else if ($contactCnt >=  32)
                $deltaT = 3.256 * pow($prumerI, 1.85);
        }


        $teplota = $conGen->getTemp() + $deltaT;

        $piT = exp((-0.14 / (8.617 * pow(10, -5))) * (1 / ($teplota + 273) - 1 / 298));

        $spoj = $conGen->getMatingFactor();
        if ($spoj <= 0.05)
            $piK = 1;
        else if ($spoj > 0.05 && $spoj <= 0.5)
            $piK = 1.5;
        else if ($spoj > 0.5 && $spoj <= 5)
            $piK = 2;
        else if ($spoj > 5 && $spoj <= 50)
            $piK = 3;
        else
            $piK = 4;

        $qual = $conGen->getQuality();
        If ($qual == "MIL-SPEC")
            $piQ = 1;
        Else
            $piQ = 2;

        $sEnv = $conGen->getEnvironment();
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM Environment e
                        WHERE e.ID_Section = 151');
        $stmt->execute();
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];

        $lambda = $base * $piT * $piK * $piQ * $piE * pow(10, -6);
        return $lambda;
    }


    public function setLamsConGen(ConnectorGen $conGen, $pcbID) {
        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $conGen->setTemp($system->getTemp() + $conGen->getPassiveTemp());

        $lambda = $this->lamConGen($conGen);

        $conGen->setLam($lambda);
        $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda);
        $system->setLam($system->getLam() + $lambda);

        $conGen->setPCBID($pcb);
        try {
            $em = $this->doctrine->getManager();
            $em->persist($conGen);
            $em->persist($pcb);
            $em->persist($system);
            $em->flush();

        } catch (\Exception $e) {
            return $e;
        }
        return "";
    }
}