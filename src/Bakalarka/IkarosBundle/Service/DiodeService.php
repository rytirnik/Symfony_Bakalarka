<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\DiodeLF;


class DiodeService {
	
	protected $doctrine;
	
	public function __construct(Registry $doctrine, SystemService $systemService, PcbService $pcbService) {
		$this->doctrine = $doctrine;
        $this->systemService = $systemService;
        $this->pcbService = $pcbService;
	}

	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:DiodeLF');
	}

//====================================================================================================================
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	public function getItem($id) {
		return $this->getRepository()->find($id);
	}
//====================================================================================================================

    public function getDiodeLFQualityChoices() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM QualityDiodeopto');
        $stmt->execute();
        $diodeQualityAll = $stmt->fetchAll();

        foreach($diodeQualityAll as $q) {
            $QualityChoices[$q['Description']] = $q['Description'];
        }
        return $QualityChoices;
    }

//====================================================================================================================

    public function getDiodeLFAppChoices() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM DiodeLFApplication');
        $stmt->execute();
        $diodeAppAll = $stmt->fetchAll();

        foreach($diodeAppAll as $q) {
            $AppChoices[$q['Description']] = $q['Description'];
        }
        return $AppChoices;
    }

//====================================================================================================================
    public function getActiveDiodesLF ($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT neco.*, diode.*
                        FROM DiodeLF diode JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON pcb.ID_PCB=part.PCB_ID
                        WHERE pcb.ID_PCB = :id AND pcb.DeleteDate IS NULL AND part.DeleteDate IS NULL) AS neco
                        ON diode.ID_Part = neco.ID_Part');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }
//====================================================================================================================
    public function getContactConstructionChoices() {
        $ccChoices['Metallurgically Bonded'] = 'Metallurgically Bonded';
        $ccChoices['Non-Metal. Bonded and Spring Loaded Contacts'] = 'Non-Metal. Bonded and Spring Loaded Contacts';
        $ccChoices['Worstcase'] = 'Worst case';
        return $ccChoices;
    }
//====================================================================================================================
    public function getContactConstructionDesc($cc) {
        if($cc == 'Metallurgically Bonded')
            return 'Metal. Bonded';
        else if ($cc == 'Non-Metal. Bonded and Spring Loaded Contacts')
            return 'Non-Metal. Bonded';
        else
            return 'Worst case';
    }

//====================================================================================================================
    public function getContactConstructionValue($cc) {
        if($cc == 'Metallurgically Bonded')
            return 1;
        else
            return 2;
    }

//====================================================================================================================
    public function getApplication ($appDesc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM DiodeLFApplication app
                        WHERE app.Description = :desc');
        $stmt->execute(array(':desc' => $appDesc));
        $diodeApp = $stmt->fetchAll();

        return $diodeApp[0];
    }

//====================================================================================================================
    public function getQuallity ($qualityDesc) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM QualityDiodeopto qual
                        WHERE qual.Description = :desc');
        $stmt->execute(array(':desc' => $qualityDesc));
        $diodeApp = $stmt->fetchAll();
        return $diodeApp[0];
    }

//====================================================================================================================
    public function calculateLam (DiodeLF $diode, $pcbID) {
        $sEnv = $diode->getEnvironment();
        /*$stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                       FROM Environment e
                       WHERE e.ID_Section = 61');
        $stmt->execute();s
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];*/

        $piE = $this->systemService->getPiE(61, $sEnv);

        $pcb = $this->pcbService->getItem($pcbID);
        $system = $this->systemService->getItem($pcb->getSystemID());

        $temp = $diode->getPassiveTemp() + $diode->getDPTemp() + $system->getTemp();
        $diode->setTemp($temp);
        $app = $this->getApplication($diode->getApplication());

        $base = $app['Value'];
        if($app['Description'] == "Power Rectifier with High Voltage Stacks")
            $base /= $temp;

        $tempCategory = $app['tempCategory'];
        $stressCategory = $app['stressCategory'];

        if($tempCategory == 1)
            $piT = exp(-3091 * (1 / ($temp + 273) - 1 / 298));
        else
            $piT = exp(-1925 * (1 / ($temp + 273) - 1 / 298));

        $stressRatio = $diode->getVoltageApplied() / $diode->getVoltageRated();
        if($stressCategory == 2) {
            if($stressRatio <= 0.3)
                $piS = 0.054;
            else
                $piS = pow($stressRatio, 0.43);
        }
        else
            $piS = 1.0;

        $piC = $this->getContactConstructionValue($diode->getContactConstruction());

        $quality = $this->getQuallity($diode->getQuality());
        $piQ = $quality['Value'];

        $lambda = $base * $piT * $piS * $piC * $piQ * $piE * pow(10,-6);
        return $lambda;
    }


}