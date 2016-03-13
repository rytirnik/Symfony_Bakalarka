<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\Filter;


class FilterService {
	
	protected $doctrine;

	
	public function __construct(Registry $doctrine) {
		$this->doctrine = $doctrine;
	}
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:Filter');
	}

//====================================================================================================================
	public function getItems() {
		return $this->getRepository()->findAll();
	}
	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

//====================================================================================================================
    public function getFilterTypeAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM FilterType');
        $stmt->execute();
        $filterTypeAll = $stmt->fetchAll();
        foreach($filterTypeAll as $m) {
            $filterTypeChoices[$m['Description']] = $m['Description'];
        }
        return $filterTypeChoices;
    }

//====================================================================================================================
    public function getActiveFilters($pcbID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, f.*
                       FROM Filter f LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(f.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "filtr"');
        $stmt->execute(array(':id' => $pcbID));
        return $stmt->fetchAll();
    }

//====================================================================================================================
    public function calculateLam (Filter $filter, $pcbID = -1) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM FilterType f
                        WHERE f.Description = :d');
        $stmt->execute(array('d' => $filter->getFilterType()));
        $filterType = $stmt->fetchAll();
        $base = floatval($filterType[0]['Lamb']);

        $sEnv = $filter->getEnvironment();
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                       FROM Environment e
                       WHERE e.ID_Section = 211');
        $stmt->execute();
        $env = $stmt->fetchAll();
        $piE = $env[0][$sEnv];

        $qual = $filter->getQuality();
        if($qual == 'MIL-SPEC')
            $piQ = 1;
        else
            $piQ = 2.9;

        $lambda = $base * $piQ * $piE * pow(10, -6);
        return $lambda;
    }
}