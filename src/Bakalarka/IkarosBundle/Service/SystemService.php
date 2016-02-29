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

    public function getAllSystems() {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT s.*, u.Username,(SELECT count(p.SystemID)
                                FROM PCB p
                                WHERE p.SystemID = s.ID_System AND p.DeleteDate IS NULL) AS PCBcnt,
                            (SELECT count(part.PCB_ID)
                            FROM PCB p JOIN Part part ON (p.ID_PCB = part.PCB_ID)
                            WHERE p.SystemID = s.ID_System AND part.PCB_ID = p.ID_PCB AND part.DeleteDate IS NULL) AS PartsCnt
                        FROM System s JOIN User u ON(s.UserID = u.ID_User)');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserSystems($userID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT s.*, u.Username,(SELECT count(p.SystemID)
                                FROM PCB p
                                WHERE p.SystemID = s.ID_System AND p.DeleteDate IS NULL) AS PCBcnt,
                            (SELECT count(part.PCB_ID)
                            FROM PCB p JOIN Part part ON (p.ID_PCB = part.PCB_ID)
                            WHERE p.SystemID = s.ID_System AND part.PCB_ID = p.ID_PCB AND part.DeleteDate IS NULL) AS PartsCnt
                        FROM System s JOIN User u ON(s.UserID = u.ID_User)
                        WHERE UserID = :UserID AND s.DeleteDate IS NULL');

        $stmt->execute(array(':UserID' => $userID));
        return $stmt->fetchAll();
    }

    public function getUserActiveSystems($userID) {
        $query = $this->doctrine->getManager()
            ->createQuery('SELECT s FROM BakalarkaIkarosBundle:System s
                            WHERE s.UserID = :id AND s.DeleteDate IS NULL');
        $query->setParameters(array('id' => $userID));
        return $query->getResult();
    }

    public function getActiveSystem($systemID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT s.*, u.Username, (SELECT count(p.SystemID)
                                FROM PCB p
                                WHERE p.SystemID = :sysID AND p.DeleteDate IS NULL) AS PCBcnt,
                            (SELECT count(part.PCB_ID)
                            FROM PCB p JOIN Part part ON (p.ID_PCB = part.PCB_ID)
                            WHERE p.SystemID = :sysID AND part.PCB_ID = p.ID_PCB AND part.DeleteDate IS NULL) AS PartsCnt
                        FROM System s LEFT JOIN User u ON (u.ID_User = s.UserID)
                        WHERE s.ID_System = :sysID AND s.DeleteDate IS NULL');
        $stmt->execute(array(':sysID' => $systemID));
        return $stmt->fetch();
    }

    public function getSystem($systemID) {
        $stmt = $this->doctrine->getManager()
            ->getConnection()
            ->prepare('SELECT s.*, u.Username, (SELECT count(p.SystemID)
                                FROM PCB p
                                WHERE p.SystemID = :sysID AND p.DeleteDate IS NULL) AS PCBcnt,
                            (SELECT count(part.PCB_ID)
                            FROM PCB p JOIN Part part ON (p.ID_PCB = part.PCB_ID)
                            WHERE p.SystemID = :sysID AND part.PCB_ID = p.ID_PCB AND part.DeleteDate IS NULL) AS PartsCnt
                        FROM System s LEFT JOIN User u ON (u.ID_User = s.UserID)
                        WHERE s.ID_System = :sysID');
        $stmt->execute(array(':sysID' => $systemID));
        return $stmt->fetch();
    }

    public function setDeleteDate($system) {
        $manager = $this->doctrine->getManager();
        $system->setDeleteDate(new \DateTime());
        try {
            $manager->persist($system);
            $msg = "System " + $system->getTitle() + " byl vymazán.";

        } catch (\Exception $e) {
            $msg = "System " + $system->getTitle() + " se nepodařilo vymazat.";
            return $msg;
        }
        return "";
    }
}