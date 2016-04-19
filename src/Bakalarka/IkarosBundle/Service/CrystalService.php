<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\Crystal;


class CrystalService {

    protected $doctrine;

    public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
        $this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
    }

    protected function getRepository() {
        return $this->doctrine->getRepository('BakalarkaIkarosBundle:Crystal');
    }

//====================================================================================================================
    public function getItems() {
        return $this->getRepository()->findAll();
    }

    public function getItem($id) {
        return $this->getRepository()->find($id);
    }
//====================================================================================================================



//====================================================================================================================
    public function getActiveCrystals ($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT neco.*, crystal.*
                        FROM Crystal crystal JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON pcb.ID_PCB=part.PCB_ID
                        WHERE pcb.ID_PCB = :id AND pcb.DeleteDate IS NULL AND part.DeleteDate IS NULL) AS neco
                        ON crystal.ID_Part = neco.ID_Part');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function calculateLam (Crystal $crystal) {
        $sEnv = $crystal->getEnvironment();
        $piE = $this->systemService->getPiE(191, $sEnv);

        $base = 0.013 * pow($crystal->getFrequency(), 0.23);
        $qual = $crystal->getQuality();
        if($qual == "MIL-SPEC")
            $piQ = 1;
        else
            $piQ = 2.1;

        $lambda = $base * $piQ * $piE * pow(10,-6);

        return $lambda;
    }


}