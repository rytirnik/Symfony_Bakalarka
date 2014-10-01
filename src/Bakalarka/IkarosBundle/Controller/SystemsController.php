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

class SystemsController extends Controller
{
    /**
     * @Route("/allSystems", name="allSystems")
     * @Template()
     */
    public function allSystemsAction()
    {
        /*$em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT s,u FROM BakalarkaIkarosBundle:System s JOIN s.UserID u');
        $systems = $query->getResult();*/


        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT s.*, u.Username,(SELECT count(p.SystemID)
                                FROM PCB p
                                WHERE p.SystemID = s.ID_System AND p.DeleteDate IS NULL) AS PCBcnt,
                            (SELECT count(part.PCB_ID)
                            FROM PCB p JOIN Part part ON (p.ID_PCB = part.PCB_ID)
                            WHERE p.SystemID = s.ID_System AND part.PCB_ID = p.ID_PCB AND part.DeleteDate IS NULL) AS PartsCnt
                        FROM System s JOIN User u ON(s.UserID = u.ID_User)');
        $stmt->execute();
        $s = $stmt->fetchAll();

        return array('systems' => $s);
    }

    /**
     * @Route("/mySystems", name="mySystems")
     * @Template()
     */
    public function mySystemsAction($err = "")
    {
        $error = $err;

        $user = $this->get('security.context')->getToken()->getUser();

        //$em = $this->get('doctrine')->getManager();
        //$RU = $em->getRepository('System');

        //$repository = $this->getDoctrine()->getRepository('BakalarkaIkarosBundle:System');
        //$systems = $repository->findBy(array('UserID' => $user->getIDUser()));


        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT s.*, u.Username,(SELECT count(p.SystemID)
                                FROM PCB p
                                WHERE p.SystemID = s.ID_System AND p.DeleteDate IS NULL) AS PCBcnt,
                            (SELECT count(part.PCB_ID)
                            FROM PCB p JOIN Part part ON (p.ID_PCB = part.PCB_ID)
                            WHERE p.SystemID = s.ID_System AND part.PCB_ID = p.ID_PCB AND part.DeleteDate IS NULL) AS PartsCnt
                        FROM System s JOIN User u ON(s.UserID = u.ID_User)
                        WHERE UserID = :UserID AND s.DeleteDate IS NULL');

        $stmt->execute(array(':UserID' => $user->getIDUser()));
        $systems = $stmt->fetchAll();

        /*try {
            $em = $this->get('doctrine')->getManager();

            $res = new Resistor();
            $res->setTemp(10);
            $res->setLabel("R3");

            $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
            $pcb = $RU->findOneBy(array('ID_PCB' => 2));


            //$em->persist($res);

            $res->setPCBID($pcb);

            $em->persist($res);

            $em->flush();
        } catch (\Exception $e) {
            $result['e'] = $e;
        }*/

        return array('systems' => $systems, 'error' => $error);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     * @Template()
     */
    public function detailAction($id)
    {

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT s.*, u.Username, (SELECT count(p.SystemID)
                                FROM PCB p
                                WHERE p.SystemID = :sysID AND p.DeleteDate IS NULL) AS PCBcnt,
                            (SELECT count(part.PCB_ID)
                            FROM PCB p JOIN Part part ON (p.ID_PCB = part.PCB_ID)
                            WHERE p.SystemID = :sysID AND part.PCB_ID = p.ID_PCB AND part.DeleteDate IS NULL) AS PartsCnt
                        FROM System s LEFT JOIN User u ON (u.ID_User = s.UserID)
                        WHERE s.ID_System = :sysID AND s.DeleteDate IS NULL');
        $stmt->execute(array(':sysID' => $id));
        $system = $stmt->fetch();

        $stmt = $this->getDoctrine()->getManager()
        ->getConnection()
            ->prepare('SELECT *
                        FROM PCB pcb
                        LEFT JOIN (
                            SELECT pcb.ID_PCB AS id, COUNT( part.PCB_ID ) AS PartsCnt
                            FROM PCB pcb
                            JOIN Part part ON ( pcb.ID_PCB = part.PCB_ID )
                            WHERE pcb.SystemID = :sysID AND part.DeleteDate IS NULL
                            GROUP BY part.PCB_ID) AS neco ON ( neco.id = pcb.ID_PCB )
                        WHERE pcb.SystemID = :sysID AND pcb.DeleteDate IS NULL');
        $stmt->execute(array(':sysID' => $id));
        $desk = $stmt->fetchAll();


        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*
                        FROM Part p JOIN PCB pcb ON (p.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.SystemID = :sysID AND p.DeleteDate IS NULL AND pcb.DeleteDate IS NULL
                        ORDER BY p.entity_type');

        $stmt->execute(array(':sysID' => $id));
        $parts = $stmt->fetchAll();

        return array('system' => $system, 'desk' => $desk, 'parts' => $parts);
    }

    /**
     * @Route("/detailA/{id}", name="detailAdmin")
     * @Template()
     */
    public function detailAdminAction($id)
    {

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT s.*, u.Username, (SELECT count(p.SystemID)
                                FROM PCB p
                                WHERE p.SystemID = :sysID AND p.DeleteDate IS NULL) AS PCBcnt,
                            (SELECT count(part.PCB_ID)
                            FROM PCB p JOIN Part part ON (p.ID_PCB = part.PCB_ID)
                            WHERE p.SystemID = :sysID AND part.PCB_ID = p.ID_PCB AND part.DeleteDate IS NULL) AS PartsCnt
                        FROM System s LEFT JOIN User u ON (u.ID_User = s.UserID)
                        WHERE s.ID_System = :sysID');
        $stmt->execute(array(':sysID' => $id));
        $system = $stmt->fetch();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM PCB pcb
                        LEFT JOIN (
                            SELECT pcb.ID_PCB AS id, COUNT( part.PCB_ID ) AS PartsCnt
                            FROM PCB pcb
                            JOIN Part part ON ( pcb.ID_PCB = part.PCB_ID )
                            WHERE pcb.SystemID = :sysID AND part.DeleteDate IS NULL
                            GROUP BY part.PCB_ID) AS neco ON ( neco.id = pcb.ID_PCB )
                        WHERE pcb.SystemID = :sysID AND pcb.DeleteDate IS NULL');
        $stmt->execute(array(':sysID' => $id));
        $desk = $stmt->fetchAll();


        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*
                        FROM Part p JOIN PCB pcb ON (p.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.SystemID = :sysID
                        ORDER BY p.entity_type');
        $stmt->execute(array(':sysID' => $id));
        $parts = $stmt->fetchAll();

        //return array('system' => $system, 'desk' => $desk, 'parts' => $parts);
        return $this->render('BakalarkaIkarosBundle:Systems:detail.html.twig', array(
            'system' => $system, 'desk' => $desk, 'parts' => $parts
        ));
    }

    /**
     * @Route("/newSystem", name="newSystem")
     * @Template()
     */
    public function newSystemAction()
    {
        $error = "";
        $envChoices = array(
         "GB"  =>"Gb", "GF"  => "Gf", "GM"  => "Gm", "NS"  => "Ns","NU"  => "Nu", "AIC" => "Aic", "AIF" => "Aif",
         "AUC" => "Auc", "AUF" => "Auf", "ARW" => "Arw", "Sf" => "Sf", "Mf" => "Mf", "ML" => "Ml", "CL" => "Cl");
        $system = new System();
        $form = $this->createFormBuilder($system)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
            ))
            ->add('Title', 'text', array(
                'required' => true,
                'label' => 'Název systému',
                'data' => '',
                'error_bubbling' => true,
                'max_length' => 30,
            ))
            ->add('Temp', 'integer', array(
                'required' => true,
                'label' => 'Teplota',
                'error_bubbling' => true,
            ))
            ->add('Note', 'textarea', array(
                'required' => false,
                'label' => 'Poznámka (max 500 znaků)',
                'error_bubbling' => true,
                'max_length' => 500
            ))

            ->getForm();


        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $user = $this->get('security.context')->getToken()->getUser();
                try {
                    $em = $this->get('doctrine')->getManager();
                    $system->setUserID($user);
                    $system->setEnvironment($form['Environment']->getData());
                    $em->persist($system);
                    $em->flush();

                } catch (\Exception $e) {
                    $error = "Nepodařilo se uložit systém.";
                    return $this->redirect($this->generateUrl('newSystem'));
                }



                return $this->redirect($this->generateUrl('mySystems'));
            }
        }



        return $this->render('BakalarkaIkarosBundle:Systems:newSystem.html.twig', array(
            'form' => $form->createView(), 'error' => $error,
        ));
    }

    /**
     * @Route("/detailSystem/{id}", name="detailSystem")
     * @Template()
     */
     public function detailSystemAction($id) {
        $error = "";
        $envChoices = array(
            "GB"  =>"Gb", "GF"  => "Gf", "GM"  => "Gm", "NS"  => "Ns","NU"  => "Nu", "AIC" => "Aic", "AIF" => "Aif",
            "AUC" => "Auc", "AUF" => "Auf", "ARW" => "Arw", "Sf" => "Sf", "Mf" => "Mf", "ML" => "Ml", "CL" => "Cl");

        $em =  $this->getDoctrine()->getManager();
        $RU = $em->getRepository('BakalarkaIkarosBundle:System');
        $system = $RU->findOneBy(array('ID_System' => $id));

         $form = $this->createFormBuilder($system)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $system->getEnvironment()
            ))
            ->add('Title', 'text', array(
                'required' => true,
                'label' => 'Název systému',
                'data' => '',
                'error_bubbling' => true,
                'max_length' => 30,
                'data' => $system->getTitle()
            ))
            ->add('Temp', 'integer', array(
                'required' => true,
                'label' => 'Teplota',
                'error_bubbling' => true,
                'data' => $system->getTemp()
            ))
            ->add('Note', 'textarea', array(
                'required' => false,
                'label' => 'Poznámka (max 500 znaků)',
                'error_bubbling' => true,
                'max_length' => 500,
                'data' => $system->getNote()
            ))

            ->getForm();

         return array('form' => $form->createView(), 'idS' => $system->getIDSystem(),  'Title' => $system->getTitle());

     }


    /**
     * @Route("/editSystem/{id}", name="editSystem")
     * @Template()
     */
    public function editSystemAction($id) {
        $post = $this->get('request')->request;
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->form;

        $em =  $this->getDoctrine()->getManager();
        $RU = $em->getRepository('BakalarkaIkarosBundle:System');
        $system = $RU->findOneBy(array('ID_System' => $id));

        $system->setTitle($obj->Title);
        $system->setTemp($obj->Temp);
        $system->setNote($obj->Note);
        $system->setEnvironment($obj->Environment);

        try {
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
                'Title' => $system->getTitle()
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );

    }


    /**
     * @Route("/delSystem/{id}", name="delSystem")
     * @Template()
     */
    public function delSystemAction($id)
    {
        //$error = "";

        $em =  $this->getDoctrine()->getManager();
        $RU = $em->getRepository('BakalarkaIkarosBundle:System');
        $system = $RU->findOneBy(array('ID_System' => $id));
        $system->setDeleteDate(new \DateTime());
        try {
            $em->persist($system);
            $error = "System " + $system->getTitle() + " byl vymazán.";

        } catch (\Exception $e) {
            $error = "System " + $system->getTitle() + " se nepodařilo vymazat.";
            return $this->redirect($this->generateUrl('mySystems'));
        }

        $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
        $pcbs = $RU->findBy(array('SystemID' => $id, 'DeleteDate' => NULL));


        $query = $em->createQuery('SELECT p FROM BakalarkaIkarosBundle:Part p JOIN p.PCB_ID pcb
                                    WHERE pcb.SystemID = :id AND pcb.DeleteDate IS NULL AND p.DeleteDate IS NULL');
        $query->setParameters(array('id' => $id));
        $parts = $query->getResult();

        foreach($parts as $part) {
            try {
                //$part->setIsActive(0);
                $part->setDeleteDate(new \DateTime());
                $em->persist($part);
            } catch (\Exception $e) {
                $error = "Součástku " + $part->getLabel() + " se nepodařilo vymazat.";
                return $this->redirect($this->generateUrl('mySystems'));
            }
        }

        $query = $em->createQuery('SELECT p FROM BakalarkaIkarosBundle:PartSMT p JOIN p.PCB_ID pcb
                                    WHERE pcb.SystemID = :id AND pcb.DeleteDate IS NULL AND p.DeleteDate IS NULL');
        $query->setParameters(array('id' => $id));
        $partsmt = $query->getResult();

        foreach($partsmt as $part) {
            try {
                //$part->setIsActive(0);
                $part->setDeleteDate(new \DateTime());
                $em->persist($part);
            } catch (\Exception $e) {
                $error = "Součástku " + $part->getLabel() + " se nepodařilo vymazat.";
                return $this->redirect($this->generateUrl('mySystems'));
            }
        }

        foreach($pcbs as $pcb) {
            try {
                //$pcb->setIsActive(0);
                $pcb->setDeleteDate(new \DateTime());
                $em->persist($pcb);
            } catch (\Exception $e) {
                $error = "Desku " + $pcb->getLabel() + " se nepodařilo vymazat.";
                return $this->redirect($this->generateUrl('mySystems'));
            }
        }

        $em->flush();


       return $this->redirect($this->generateUrl('mySystems'));
    }





}

