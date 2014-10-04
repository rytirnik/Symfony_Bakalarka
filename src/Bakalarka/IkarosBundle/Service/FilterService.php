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
	
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}

    public function getFilterTypeAll() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM FilterType');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function lamFilter (Filter $filter) {
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