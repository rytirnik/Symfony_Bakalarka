<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\Switches;


class SwitchService {
	
	protected $doctrine;
	
	public function __construct(Registry $doctrine, $systemService) {
		$this->doctrine = $doctrine;
        $this->systemService = $systemService;
	}
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:Switches');
	}

//====================================================================================================================
	public function getItems() {
		return $this->getRepository()->findAll();
	}
	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

//====================================================================================================================
    public function getSwitchTypeAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Switch_Type');
        $stmt->execute();
        $swTypes = $stmt->fetchAll();

        foreach($swTypes as $m) {
            $swTypeChoices[$m['Description']] = $m['Description'];
        }
        return $swTypeChoices;
    }
//====================================================================================================================
    public function getSwitchTypeValue($desc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM Switch_Type s
                        WHERE s.Description = :d');
        $stmt->execute(array('d' => $$desc));
        $switchType = $stmt->fetch();
        return floatval($switchType['Lamb']);
    }
//====================================================================================================================
    public function getActiveSwitches($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, s.*
                       FROM Switches s LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(s.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "spínač"');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function calculateLam (Switches $switch, $pcbID = -1) {

        $base = $this->getSwitchTypeValue($switch->getSwitchType());
        $sEnv = $switch->getEnvironment();

        $piE = $this->systemService->getPiE(141, $sEnv);

        $stress = $switch->getOperatingCurrent() / $switch->getRatedResistiveCurrent();
        $load = $switch->getLoadType();

        switch ($load) {
            case 'Resistive':
                $piL = exp(pow(($stress / 0.8), 2));
                break;
            case 'Inductive':
                $piL = exp(pow(($stress / 0.4), 2));
                break;
            case 'Lamp':
                $piL = exp(pow(($stress / 0.2), 2));
                break;
            case 'Worstcase':
                $piL = exp(pow(($stress / 0.2), 2));
                break;
        }


        $typeS = $switch->getSwitchType();
        if($typeS == 'Pushbutton' || $typeS == 'Toggle')
            $piC = pow($switch->getContactCnt(), 0.33);
        else
            $piC = 1;

        $qual = $switch->getQuality();
        if($qual == 'MIL-SPEC')
            $piQ = 1;
        else
            $piQ = 2;

        $lambda = $base * $piL * $piC * $piQ * $piE * pow(10, -6);

        return $lambda;
    }
}