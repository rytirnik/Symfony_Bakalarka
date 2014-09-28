<?php

namespace Bakalarka\IkarosBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Bakalarka\IkarosBundle\Entity\System;


class SystemService {
	
	protected $doctrine;


	
	public function __construct(Registry $doctrine) {
		$this->doctrine = $doctrine;

	}
	
	protected function getRepository() {
		return $this->doctrine->getRepository('BakalarkaIkarosBundle:System');
	}
	
	public function getItems() {
		return $this->getRepository()->findAll();
	}

	
	public function getItem($id) {
		return $this->getRepository()->find($id);
	}


    public function getEnvChoices () {
        return array(
            "GB"  => "Gb", "GF"  => "Gf", "GM"  => "Gm", "NS"  => "Ns","NU"  => "Nu", "AIC" => "Aic", "AIF" => "Aif",
            "AUC" => "Auc", "AUF" => "Auf", "ARW" => "Arw", "Sf" => "Sf", "Mf" => "Mf", "ML" => "Ml", "CL" => "Cl");
    }


}