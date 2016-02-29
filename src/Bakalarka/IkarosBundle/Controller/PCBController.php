<?php

namespace Bakalarka\IkarosBundle\Controller;

use Bakalarka\IkarosBundle\Entity\PartSMT;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

use Bakalarka\IkarosBundle\Entity\User;
use Bakalarka\IkarosBundle\Entity\Resistor;
use Bakalarka\IkarosBundle\Entity\System;
use Bakalarka\IkarosBundle\Entity\PCB;


class PCBController extends Controller
{

//====================================================================================================================

    /**
     * @Route("/newPCB/{id}", name="newPCB")
     * @Template()
     */
    public function newPCBAction($id=-1)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        //$RU = $em->getRepository('BakalarkaIkarosBundle:System');
        //$systems = $RU->findAll(array('UserID' => $user->getIDUser()));

        $servicePCB = $this->get('ikaros_pcbService');
        $serviceSystem = $this->get('ikaros_systemService');

        if($id == -1) {
            /*$query = $em->createQuery('SELECT s FROM BakalarkaIkarosBundle:System s WHERE s.UserID = :id AND s.DeleteDate IS NULL');
            $query->setParameters(array('id' => $user->getIDUser()));
            $systems = $query->getResult();*/
            $systems = $serviceSystem->getUserActiveSystems($user->getIDUser());

            if(!$systems) {
                return $this->render('BakalarkaIkarosBundle:PCB:newPCB.html.twig', array(
                    'form' => ""
                ));
            }
            foreach($systems as $system) {
                $sysID[$system->getIDSystem()] = $system->getTitle();
            }
        }


        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM EquipmentType');
        $stmt->execute();
        $eqs = $stmt->fetchAll();*/

        $EquipChoices = $servicePCB->getEquipmentTypes();

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM SubstrateMaterial ORDER BY Description');
        $stmt->execute();
        $mat = $stmt->fetchAll();*/

        $MatChoices = $servicePCB->getMaterials();

        /*foreach($eqs as $eq) {
           $EquipChoices[$eq['ID_EquipType']] = $eq['Description'];
        }

        foreach($mat as $m) {
            $MatChoices[$m['ID_SubstrateMat']] = $m['Description'];
        }*/

        $pcb = new PCB();

        if($id == -1) {
            $form = $this->createFormBuilder($pcb)
               ->add('system', 'choice', array(
                    'mapped' => false,
                    'label' => 'Systém',
                    'choices' => $sysID,
                    'required' => true,
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název desky',
                    'data' => '',
                    'error_bubbling' => true,
                    'max_length' => 30,
                ))
                ->add('Lifetime', 'integer', array(
                    'label' => 'Životnost',
                    'required' => true,
                    'error_bubbling' => true,
                    'attr' => array('min' => 1)
                ))
                ->add('EquipType', 'choice', array(
                    'label' => 'Aplikace v odvětví',
                    'choices' => $EquipChoices,
                    'required' => true,
                ))
                ->add('SubstrateMaterial', 'choice', array(
                    'label' => 'Materiál',
                    'choices' => $MatChoices,
                    'required' => true,
                ))


                ->getForm();
        }
        else {
            $form = $this->createFormBuilder($pcb)
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název desky',
                    'data' => '',
                    'error_bubbling' => true,
                    'max_length' => 30,
                ))
                ->add('Lifetime', 'integer', array(
                    'label' => 'Životnost (roky)',
                    'required' => true,
                    'error_bubbling' => true,
                    'attr' => array('min' => 1)
                ))
                ->add('EquipType', 'choice', array(
                    'label' => 'Aplikace v odvětví',
                    'choices' => $EquipChoices,
                    'required' => true,
                ))
                ->add('SubstrateMaterial', 'choice', array(
                    'label' => 'Materiál',
                    'choices' => $MatChoices,
                    'required' => true,
                ))

                ->getForm();
        }


        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                if($id == -1) {
                    $systemID = $form['system']->getData();
                    //$system = $this->getDoctrine()->getRepository('BakalarkaIkarosBundle:System')->findOneBy(array('ID_System' => $s));
                    $system = $serviceSystem->getItem($systemID);
                    $pcb->setSystemID($system);
                }
                else {
                    //$RU = $em->getRepository('BakalarkaIkarosBundle:System');
                    //$system = $RU->findOneBy(array('ID_System' => $id));
                    $system = $serviceSystem->getItem($id);
                    $pcb->setSystemID($system);
                }

                try {
                    $em->persist($pcb);
                    $em->flush();

                } catch (\Exception $e) {
                    $error = "Nepodařilo se uložit desku.";
                    return $this->redirect($this->generateUrl('newSystem'));
                }

                //echo $form['system']->getData();

                return $this->redirect($this->generateUrl('detail',array('id'=>$id)));
            }
        }


        $form2 = $this->createFormBuilder($pcb)
            ->add('Quality', 'choice', array(
                'mapped' => false,
                'label' => 'Systém',
                'choices' => array(1=>"MIL-SPEC", 2=>"Lower"),
                'required' => true,
            ))
            ->add('Layers', 'integer', array(
                    'required' => false,
                    'label' => 'Počet vrstev',
                    'error_bubbling' => true,
                    'attr' => array('min'=>1, 'max'=>18)
                ))
            ->add('SolderingPointAuto', 'integer', array(
                'label' => 'Bodů pájení automaticky',
                'error_bubbling' => true,
                'required' => true,
                'attr' => array('min'=>0)
            ))
            ->add('SolderingPointHand', 'integer', array(
                'label' => 'Bodů pájení ručně',
                'error_bubbling' => true,
                'required' => true,
                'attr' => array('min'=>0)
            ))

            ->getForm();

        $pcbSMT = new PartSMT();
        $form3 = $this->createFormBuilder($pcbSMT)
            ->add('LeadConfig', 'choice', array(
                'mapped' => false,
                'label' => 'Typy vývodů',
                'choices' => array(1=>"Leadless", 150=>"J/S Lead", 5000=>"Gull Wing"),
                'required' => true,
            ))
            ->add('TCEPackage', 'choice', array(
                'mapped' => false,
                'label' => 'Materiál pouzdra',
                'choices' => array(7=>"Plastic", 6=>"Ceramic"),
                'required' => true,
            ))
            ->add('Height', 'integer', array(
                'required' => false,
                'label' => 'Délka [mil]',
                'error_bubbling' => true,
                'attr' => array('min'=>0)
            ))
            ->add('Width', 'integer', array(
                'required' => false,
                'label' => 'Šířka [mil]',
                'error_bubbling' => true,
                'attr' => array('min'=>0)
            ))
            ->add('TempDissipation', 'integer', array(
                'label' => 'Oteplení ztrát. výkonem [°C]',
                'error_bubbling' => true,
                'required' => false,
                'attr' => array('min'=>0)
            ))
            ->add('Cnt', 'integer', array(
                'label' => 'Počet součástek',
                'error_bubbling' => true,
                'required' => true,
                'attr' => array('min'=>1)
            ))

            ->getForm();


        return $this->render('BakalarkaIkarosBundle:PCB:newPCB.html.twig', array(
            'form' => $form->createView(), 'idSend' => $id, 'form2' => $form2->createView(), 'form3' => $form3->createView()
        ));
    }
//====================================================================================================================

    /**
     * @Route("/delDesk/{id}", name="delDesk")
     * @Template()
     */
    public function delDeskAction($id)
    {

        //$RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
        //$pcb = $RU->findOneBy(array('ID_PCB' => $id));

        $servicePCB = $this->get('ikaros_pcbService');
        $pcb = $servicePCB->getItem($id);

        $sysID = $pcb->getSystemID();
        //$sysID = $sys->getIDSystem();


        //$RU = $em->getRepository('BakalarkaIkarosBundle:System');
        //$system = $RU->findOneBy(array('ID_System' => $sys));

        $serviceSystem = $this->get('ikaros_systemService');
        $system = $serviceSystem->getItem($sysID);
        $system->setLam($system->getLam() - $pcb->getSumLam() - $pcb->getSumPartsLam());

        //$pcb->setIsActive(0);


        //$RU = $em->getRepository('BakalarkaIkarosBundle:Part');
        //$parts = $RU->findBy(array('PCB_ID' => $id, 'DeleteDate' => NULL));

        $servicePart = $this->get('ikaros_partService');
        /*$parts = $servicePart->getActivePartsByPcbID($id);
        $msg = $servicePart->setDeleteDateToParts($parts);

        if($msg)    //soucastka neulozena
            return $this->redirect($this->generateUrl('mySystems'));*/

        /*foreach($parts as $part) {
            try {
                //$part->setIsActive(0);
                $part->setDeleteDate(new \DateTime());
                $em->persist($part);
            } catch (\Exception $e) {
                $error = "Součástku " + $part->getLabel() + " se nepodařilo vymazat.";
                return $this->redirect($this->generateUrl('mySystems'));
            }
        }*/

        //$RU = $em->getRepository('BakalarkaIkarosBundle:PartSMT');
        //$partsmt = $RU->findBy(array('PCB_ID' => $id, 'DeleteDate' => NULL));
        /*$partsmt = $servicePCB->getActivePartsSmtByPcbID($id);
        $msg = $servicePart->setDeleteDateToParts($partsmt);

        if($msg)    //soucastka neulozena
            return $this->redirect($this->generateUrl('mySystems'));*/

        /*foreach($partsmt as $part) {
            try {
                //$part->setIsActive(0);
                $part->setDeleteDate(new \DateTime());
                $em->persist($part);
            } catch (\Exception $e) {
                $error = "Součástku " + $part->getLabel() + " se nepodařilo vymazat.";
                return $this->redirect($this->generateUrl('mySystems'));
            }
        }*/

        $parts = $servicePart->getActivePartsByPcbID($id);
        $msgParts = $servicePart->setDeleteDateToParts($parts);

        $partsmt = $servicePCB->getActivePartsSmtByPcbID($id);
        $msgSmt = $servicePart->setDeleteDateToParts($partsmt);

        $msgPcb = $servicePCB->setDeleteDateToPcb($pcb, $system);

        if($msgParts || $msgSmt || $msgPcb)
            return $this->redirect($this->generateUrl('mySystems'));

        /*    $pcb->setDeleteDate(new \DateTime());
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pcb);
            $em->persist($system);
        } catch (\Exception $e) {
            $error = "Desku " + $pcb->getLabel() + " se nepodařilo vymazat.";
            return $this->redirect($this->generateUrl('mySystems'));
        }*/

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirect($this->generateUrl('mySystems'));
    }
//====================================================================================================================

    /**
     * @Route("/delDesk", name="delDeskAjax")
     * @Template()
     */
    public function delDeskAjaxAction()
    {
        $post = $this->get('request')->request;
        $id = $post->get('id');

        $em =  $this->getDoctrine()->getManager();
        //$RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
        //$pcb = $RU->findOneBy(array('ID_PCB' => $id));

        $servicePCB = $this->get('ikaros_pcbService');
        $pcb = $servicePCB->getItem($id);

        $sysID = $pcb->getSystemID();
        //$sys = $sys->getIDSystem();

        //$RU = $em->getRepository('BakalarkaIkarosBundle:System');
        //$system = $RU->findOneBy(array('ID_System' => $sys));

        $serviceSystem = $this->get('ikaros_systemService');
        $system = $serviceSystem->getItem($sysID);

        $system->setLam($system->getLam() - $pcb->getSumLam() - $pcb->getSumPartsLam());

        $servicePart = $this->get('ikaros_partService');
        $parts = $servicePart->getActivePartsByPcbID($id);
        $msgParts = $servicePart->setDeleteDateToParts($parts);

        $partsmt = $servicePCB->getActivePartsSmtByPcbID($id);
        $msgSmt = $servicePart->setDeleteDateToParts($partsmt);

        $msgPcb = $servicePCB->setDeleteDateToPcb($pcb, $system);

        if($msgParts || $msgSmt || $msgPcb) {   //chyba -> neulozeno
            if($msgParts)
                $msg = $msgParts;
            else if($msgSmt)
                $msg = $msgSmt;
            else
                $msg = $msgPcb;
            return new Response(
                json_encode(array(
                    'e' => $msg
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );
        }

        /*try {
            //$pcb->setIsActive(0);

            $RU = $em->getRepository('BakalarkaIkarosBundle:Part');
            $parts = $RU->findBy(array('PCB_ID' => $id, 'DeleteDate' => NULL));
            foreach($parts as $part) {
                //$part->setIsActive(0);
                $part->setDeleteDate(new \DateTime());
                $em->persist($part);
            }
            $RU = $em->getRepository('BakalarkaIkarosBundle:PartSMT');
            $partsmt = $RU->findBy(array('PCB_ID' => $id, 'DeleteDate' => NULL));
            foreach($partsmt as $part) {
                //$part->setIsActive(0);
                $part->setDeleteDate(new \DateTime());
                $em->persist($part);
            }
            $pcb->setDeleteDate(new \DateTime());
            $em->persist($pcb);
            $em->persist($system);
        } catch (\Exception $e) {
            $error = "Desku " + $pcb->getLabel() + " se nepodařilo vymazat.";
            //return $this->redirect($this->generateUrl('mySystems'));
            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                //HTTP status
                400,
                //headers
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );
        }*/

        /*$pcb->setDeleteDate(new \DateTime());
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pcb);
            $em->persist($system);
        } catch (\Exception $e) {
            $error = "Desku " + $pcb->getLabel() + " se nepodařilo vymazat.";
            return $this->redirect($this->generateUrl('mySystems'));
        }*/

        $em =  $this->getDoctrine()->getManager();
        $em->flush();
        return new Response(
            json_encode(array(
                'e' => "OK",
                'lamS' =>  $system->getLam()
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );

        //return $this->redirect($this->generateUrl('mySystems'));
    }

//====================================================================================================================

    /**
     * @Route("/newPCBA", name="newPCBAjax")
     * @Template()
     */
    public function newPCBAjaxAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $wire = $post->get('wire');
        $smt = $post->get('smt');

        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->form;

        if($id == -1) {
            $id = intval($obj->system);
        }

        $em =  $this->getDoctrine()->getManager();
        //$RU = $em->getRepository('BakalarkaIkarosBundle:System');
        //$system = $RU->findOneBy(array('ID_System' => $id));

        $serviceSystem = $this->get('ikaros_systemService');
        $servicePCB = $this->get('ikaros_pcbService');

        $system = $serviceSystem->getItem($id);

        $sEnv = $system->getEnvironment();
        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM Environment e
                        WHERE e.ID_Section = 161 OR e.ID_Section = 162 ORDER BY e.ID_Section ASC');
        $stmt->execute();
        $env = $stmt->fetchAll();*/

        $env = $servicePCB->getEnvironmentPcb();

        $piE = $env[0][$sEnv];
        $dt = $env[1][$sEnv];

        $pcb = new PCB();
        $pcb->setLabel($obj->Label);
        $pcb->setLifetime(intval($obj->Lifetime));

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM EquipmentType e
                        WHERE e.ID_EquipType = :id');
        $stmt->execute(array(':id' => intval($obj->EquipType)));
        $eq = $stmt->fetchAll();*/

        $equipID = intval($obj->EquipType);
        $eq = $servicePCB->getEquipmentTypeByID($equipID);

        $pcb->setEquipType($eq[0]['Description']);

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT m.*
                        FROM SubstrateMaterial m
                        WHERE m.ID_SubstrateMat = :id');
        $stmt->execute(array(':id' => intval($obj->SubstrateMaterial)));
        $m = $stmt->fetchAll();*/

        $matID = intval($obj->SubstrateMaterial);
        $m = $servicePCB->getMaterialByID($matID);
        $pcb->setSubstrateMaterial($m[0]['Description']);

        $CR = $eq[0]['Value'];
        $alfaS = $m[0]['Value'];
        $lambda = 0;
        $LamSMT = 0;



        if($wire == "true") {
            $pcb->setQuality(intval($obj->Quality));
            $pcb->setLayers(intval($obj->Layers));
            $pcb->setSolderingPointAuto(intval($obj->SolderingPointAuto));
            $pcb->setSolderingPointHand(intval($obj->SolderingPointHand));

            //$lambda = $this->lamPCBwire($pcb, $piE);
            $lambda = $servicePCB->lamPCBwire($pcb, $piE);
            $pcb->setLam($lambda);
        }

        if($smt == "true") {
            $smtP = new PartSMT();
            $smtP->setLeadConfig(intval($obj->LeadConfig));
            $smtP->setTCEPackage(intval($obj->TCEPackage));
            $smtP->setCnt(intval($obj->Cnt));
            $smtP->setHeight(intval($obj->Height));
            $smtP->setWidth(intval($obj->Width));
            $smtP->setTempDissipation(intval($obj->TempDissipation));
            $smtP->setPCBID($pcb);
            /*$piLC = $smtP->getLeadConfig();
            $h = 5;
            $delka = $smtP->getHeight();
            $sirka = $smtP->getWidth();
            $d = (sqrt(pow($delka,2) + pow($sirka,2))) / 2;*/

            //$dt = 7; //ZJISTI ZE SYSTEMU!!!!
            /*$alfaCC = $smtP->getTCEPackage();
            $otepleni = $smtP->getTempDissipation();
            $y = Abs(($alfaS * $dt) - ($alfaCC * ($dt + $otepleni))) * pow(10, -6);
            $nf = $piLC * 3.5 * pow(($d / (0.65 * $h) * $y), -2.26);
            $asmt = $nf / $CR;
            $zivot = $pcb->getLifetime();
            $x = ($zivot * 8760) / $asmt;

            if ($x <= 0.1) $ecf = 0.13;
            else if ($x > 0.1 && $x <= 0.2) $ecf = 0.15;
            else if ($x > 0.2 && $x <= 0.3) $ecf = 0.23;
            else if ($x > 0.3 && $x <= 0.4) $ecf = 0.31;
            else if ($x > 0.4 && $x <= 0.5) $ecf = 0.41;
            else if ($x > 0.5 && $x <= 0.6) $ecf = 0.51;
            else if ($x > 0.6 && $x <= 0.7) $ecf = 0.61;
            else if ($x > 0.7 && $x <= 0.8) $ecf = 0.68;
            else if ($x > 0.8 && $x <= 0.9) $ecf = 0.76;
            else $ecf = 1;

            $LamSMT = $ecf / $asmt;
            $LamSMT *= $smtP->getCnt();*/

            $zivot = $pcb->getLifetime();
            //$LamSMT = $this->lamPCBsmt($smtP, $CR, $alfaS, $zivot, $dt);
            $LamSMT = $servicePCB->lamPCBsmt($smtP, $CR, $alfaS, $zivot, $dt);

            $smtP->setLam($LamSMT);
            //$pcb->setSumLam($LamSMT);
        }

        //if($layers > 2)
        //    $piC = 0.65 * pow($layers, 0.63);

        //$lambda = $b * (($pAuto * $piC) + ($pHand * ($piC + 13))) * $piQ * $piE * pow(10, -6);


        $sumLam = $lambda + $LamSMT;
        if($sumLam > 0) {
            $pcb->setSumLam($sumLam);
            $system->setLam($system->getLam() + $sumLam);
        }

        $pcb->setSystemID($system);

        try {
            $em->persist($pcb);
            if($smt == "true")
                $em->persist($smtP);
            if($sumLam > 0)
                $em->persist($system);
        } catch (\Exception $e) {
             return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );
        }

        $em->flush();

        $url = $this->generateUrl('detailPCB', array('id' => $pcb->getIDPCB()));

        return new Response(
            json_encode(array(
                //'smtP' => $LamSMT,
                //'lam' => $lambda,
                //'sum' => $sumLam
                'url' => $url
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }
//====================================================================================================================

    /**
     * @Route("/detailPCB/{id}", name="detailPCB")
     * @Template()
     */
    public function detailPCBAction($id) {

        $em =  $this->getDoctrine()->getManager();
        /*$RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
        $pcb = $RU->findOneBy(array('ID_PCB' => $id));

        $RU = $em->getRepository('BakalarkaIkarosBundle:PartSMT');
        $smt = $RU->findBy(array('PCB_ID' => $id, 'DeleteDate' => NULL));*/

        $servicePCB = $this->get('ikaros_pcbService');
        $pcb = $servicePCB->getItem($id);
        $smt = $servicePCB->getActiveSmt($id);


        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM EquipmentType');
        $stmt->execute();
        $eqs = $stmt->fetchAll();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM SubstrateMaterial ORDER BY Description');
        $stmt->execute();
        $mat = $stmt->fetchAll();

        foreach($eqs as $eq) {
            $EquipChoices[$eq['ID_EquipType']] = $eq['Description'];
        }

        foreach($mat as $m) {
            $MatChoices[$m['ID_SubstrateMat']] = $m['Description'];
        }*/

        $EquipChoices = $servicePCB->getEquipmentTypes();
        $MatChoices = $servicePCB->getMaterials();

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM EquipmentType e
                        WHERE e.Description = :d');
        $stmt->execute(array(':d' => $pcb->getEquipType()));
        $eq = $stmt->fetchAll();*/

        $eqT = $servicePCB->getEquipmentTypeByDesc($pcb->getEquipType());
        $eq = $eqT[0]['ID_EquipType'];

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT m.*
                        FROM SubstrateMaterial m
                        WHERE m.Description = :mat');
        $stmt->execute(array(':mat' => $pcb->getSubstrateMaterial()));
        $m = $stmt->fetchAll();*/

        $m = $servicePCB->getMaterialByDesc($pcb->getSubstrateMaterial());
        $mat = $m[0]['ID_SubstrateMat'];

        $form = $this->createFormBuilder($pcb)
            ->add('Label', 'text', array(
                'required' => true,
                'label' => 'Název desky',
                'data' => $pcb->getLabel(),
                'error_bubbling' => true,
                'max_length' => 30,
            ))
            ->add('Lifetime', 'integer', array(
                'label' => 'Životnost (roky)',
                'required' => true,
                'error_bubbling' => true,
                'attr' => array('min' => 1),
                'data' => $pcb->getLifetime()
            ))
            ->add('EquipType', 'choice', array(
                'label' => 'Aplikace v odvětví',
                'choices' => $EquipChoices,
                'required' => true,
                'data' => $eq
            ))
            ->add('SubstrateMaterial', 'choice', array(
                'label' => 'Materiál',
                'choices' => $MatChoices,
                'required' => true,
                'data' => $mat
            ))

            ->getForm();

        $form2 = $this->createFormBuilder($pcb)
            ->add('Quality', 'choice', array(
                'mapped' => false,
                'label' => 'Kvalita',
                'choices' => array(1=>"MIL-SPEC", 2=>"Lower"),
                'required' => true,
                'data' => $pcb->getQuality()
            ))
            ->add('Layers', 'integer', array(
                'required' => false,
                'label' => 'Počet vrstev',
                'error_bubbling' => true,
                'attr' => array('min'=>1, 'max'=>18),
                'data' => $pcb->getLayers()
            ))
            ->add('SolderingPointAuto', 'integer', array(
                'label' => 'Bodů pájení automaticky',
                'error_bubbling' => true,
                'required' => true,
                'attr' => array('min'=>0),
                'data' => $pcb->getSolderingPointAuto()
            ))
            ->add('SolderingPointHand', 'integer', array(
                'label' => 'Bodů pájení ručně',
                'error_bubbling' => true,
                'required' => true,
                'attr' => array('min'=>0),
                'data' => $pcb->getSolderingPointHand()
            ))

            ->getForm();

        $Psmt=array();
        $form3 = $this->createFormBuilder($Psmt)
            ->add('LeadConfig', 'choice', array(
                'mapped' => false,
                'label' => 'Typy vývodů',
                'choices' => array(1=>"Leadless", 150=>"J/S Lead", 5000=>"Gull Wing"),
                'required' => true,
            ))
            ->add('TCEPackage', 'choice', array(
                'mapped' => false,
                'label' => 'Materiál pouzdra',
                'choices' => array(7=>"Plastic", 6=>"Ceramic"),
                'required' => true,
            ))
            ->add('Height', 'integer', array(
                'required' => false,
                'label' => 'Délka [mil]',
                'error_bubbling' => true,
                'attr' => array('min'=>0)
            ))
            ->add('Width', 'integer', array(
                'required' => false,
                'label' => 'Šířka [mil]',
                'error_bubbling' => true,
                'attr' => array('min'=>0)
            ))
            ->add('TempDissipation', 'integer', array(
                'label' => 'Oteplení ztrát. výkonem [°C]',
                'error_bubbling' => true,
                'required' => false,
                'attr' => array('min'=>0)
            ))
            ->add('Cnt', 'integer', array(
                'label' => 'Počet součástek',
                'error_bubbling' => true,
                'required' => true,
                'attr' => array('min'=>1)
            ))

            ->getForm();



        return $this->render('BakalarkaIkarosBundle:PCB:detailPCB.html.twig', array(
            'form' => $form->createView(), 'idSend' => $id, 'form2' => $form2->createView(), 'form3' => $form3->createView(),
            'smt' => $smt, 'pcb' => $pcb
        ));
    }
//====================================================================================================================

    /**
     * @Route("/editPCB/{id}", name="editPCB")
     * @Template()
     */
    public function editPCBAction($id) {
        $post = $this->get('request')->request;
        $mode = $post->get('mode');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->form;

        $em =  $this->getDoctrine()->getManager();
        /*$RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
        $pcb = $RU->findOneBy(array('ID_PCB' => $id));

        $RU = $em->getRepository('BakalarkaIkarosBundle:System');
        $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));*/

        $servicePCB = $this->get('ikaros_pcbService');
        $serviceSystem = $this->get('ikaros_systemService');
        $pcb = $servicePCB->getItem($id);
        $system = $serviceSystem->getItem($pcb->getSystemID());


        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT e.*
                        FROM Environment e
                        WHERE e.ID_Section = 161 OR e.ID_Section = 162 ORDER BY e.ID_Section ASC');
        $stmt->execute();
        $env = $stmt->fetchAll();*/

        $sEnv = $system->getEnvironment();
        $env = $servicePCB->getEnvironmentPcb();
        $piE = $env[0][$sEnv];
        $dt = $env[1][$sEnv];

        $servicePCB = $this->get('ikaros_pcbService');

        switch ($mode) {
            case 1:
                $pcb->setLabel($obj->Label);
                $pcb->setLifetime(intval($obj->Lifetime));
                /*$stmt = $this->getDoctrine()->getManager()
                    ->getConnection()
                    ->prepare('SELECT e.*
                        FROM EquipmentType e
                        WHERE e.ID_EquipType = :id');
                $stmt->execute(array(':id' => intval($obj->EquipType)));
                $eq = $stmt->fetchAll();*/

                $eq = $servicePCB->getEquipmentTypeByID(intval($obj->EquipType));

                /*$stmt = $this->getDoctrine()->getManager()
                    ->getConnection()
                    ->prepare('SELECT m.*
                        FROM SubstrateMaterial m
                        WHERE m.ID_SubstrateMat = :id');
                $stmt->execute(array(':id' => intval($obj->SubstrateMaterial)));
                $m = $stmt->fetchAll();*/

                $m = $servicePCB->getMaterialByID(intval($obj->SubstrateMaterial));

                $pcb->setEquipType($eq[0]['Description']);
                $pcb->setSubstrateMaterial($m[0]['Description']);
                break;
            case 2:
                $pcb->setQuality(intval($obj->Quality));
                $pcb->setLayers(intval($obj->Layers));
                $pcb->setSolderingPointAuto(intval($obj->SolderingPointAuto));
                $pcb->setSolderingPointHand(intval($obj->SolderingPointHand));
                //$lambda = $this->lamPCBwire($pcb, $piE);
                $lambda = $servicePCB->lamPCBwire($pcb, $piE);
                $oldLam = $pcb->getLam();
                $system->setLam($system->getLam() - $oldLam + $lambda);
                $pcb->setSumLam($pcb->getSumLam() - $oldLam + $lambda);
                $pcb->setLam($lambda);
                break;
            case 3:
                $smtP = new PartSMT();
                $smtP->setLeadConfig(intval($obj->LeadConfig));
                $smtP->setTCEPackage(intval($obj->TCEPackage));
                $smtP->setCnt(intval($obj->Cnt));
                $smtP->setHeight(intval($obj->Height));
                $smtP->setWidth(intval($obj->Width));
                $smtP->setTempDissipation(intval($obj->TempDissipation));
                $smtP->setPCBID($pcb);


                /*$eqDes = $pcb->getEquipType();
                $mDes = $pcb->getSubstrateMaterial();
                $stmt = $this->getDoctrine()->getManager()
                    ->getConnection()
                    ->prepare('SELECT e.*
                        FROM EquipmentType e
                        WHERE e.Description = :ed');
                $stmt->execute(array(':ed' => $eqDes));
                $eq = $stmt->fetchAll();

                $stmt = $this->getDoctrine()->getManager()
                    ->getConnection()
                    ->prepare('SELECT m.*
                        FROM SubstrateMaterial m
                        WHERE m.Description = :md');
                $stmt->execute(array(':md' => $mDes));
                $m = $stmt->fetchAll();*/

                $eq = $servicePCB->getEquipmentTypeByDesc($pcb->getEquipType());
                $m = $servicePCB->getMaterialByDesc($pcb->getSubstrateMaterial());


                $CR = $eq[0]['Value'];
                $alfaS = $m[0]['Value'];

                $zivot = $pcb->getLifetime();
                //$LamSMT = $this->lamPCBsmt($smtP, $CR, $alfaS, $zivot, $dt);
                $LamSMT = $servicePCB->lamPCBsmt($smtP, $CR, $alfaS, $zivot, $dt);

                $smtP->setLam($LamSMT);
                $pcb->setSumLam($pcb->getSumLam() + $LamSMT);
                $system->setLam($system->getLam() + $LamSMT);
                break;
        }


        try {
            $em->persist($pcb);
            if($mode == 3)
                $em->persist($smtP);
            if($mode != 1)
                $em->persist($system);
        } catch (\Exception $e) {
            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );
        }

        $em->flush();

        if ($mode == 3) {
            return new Response(
                json_encode(array(
                    'LeadConfig' => $smtP->getLeadConfig(intval($obj->LeadConfig)),
                    'TCEPackage' => $smtP->getTCEPackage(intval($obj->TCEPackage)),
                    'Cnt' => $smtP->getCnt(intval($obj->Cnt)),
                    'Height' => $smtP->getHeight(intval($obj->Height)),
                    'Width' => $smtP->getWidth(intval($obj->Width)),
                    'TempDissipation' => $smtP->getTempDissipation(intval($obj->TempDissipation)),
                    'Lam' => $LamSMT,
                    'idSmt' => $smtP->getIDPartSMT()
                )),
                200,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );
        }

        return new Response(
            json_encode(array(
                'Lam' => $pcb->getLam(),
                'SumLam' => $pcb->getSumLam()
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }

//====================================================================================================================

    /**
     * @Route("/delSMT", name="delSMT")
     * @Template()
     */
    public function delSMTAction($id=-1) {

        $post = $this->get('request')->request;
        if($id == -1)
            $id = $post->get('id');

        $em =  $this->getDoctrine()->getManager();
        /*$RU = $em->getRepository('BakalarkaIkarosBundle:PartSMT');
        $smt = $RU->findOneBy(array('ID_Part_SMT' => $id));


        $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
        $pcb = $RU->findOneBy(array('ID_PCB' => $smt->getPCBID()));

        $RU = $em->getRepository('BakalarkaIkarosBundle:System');
        $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));*/

        $servicePCB = $this->get('ikaros_pcbService');
        $serviceSystem = $this->get('ikaros_systemService');
        $smt = $servicePCB->getSmtByID($id);
        $pcb = $servicePCB->getItem($smt->getPCBID());
        $system = $serviceSystem->getItem($pcb->getSystemID());

        $lambda = $smt->getLam();

        $smt->setDeleteDate(new \DateTime());
        $pcb->setSumLam($pcb->getSumLam() - $lambda);
        $system->setLam($system->getLam() - $lambda);

        try {
            $em->persist($pcb);
            $em->persist($smt);
            $em->persist($system);
            $em->flush();

        } catch (\Exception $e) {
            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );
        }

        return new Response(
            json_encode(array(
                'Lam' => $lambda
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }
}

