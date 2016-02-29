<?php

namespace Bakalarka\IkarosBundle\Controller;

use Bakalarka\IkarosBundle\Entity\PartSMT;
use Bakalarka\IkarosBundle\Forms\SystemForm;
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
        $serviceSystem = $this->get('ikaros_systemService');
        $systems = $serviceSystem->getAllSystems();

        return array('systems' => $systems);
    }

//====================================================================================================================

    /**
     * @Route("/mySystems", name="mySystems")
     * @Template()
     */
    public function mySystemsAction($err = "")
    {
        $error = $err;

        $user = $this->get('security.context')->getToken()->getUser();
        $userID = $user->getIDUser();
        $serviceSystem = $this->get('ikaros_systemService');
        $systems = $serviceSystem->getUserSystems($userID);

        return array('systems' => $systems, 'error' => $error);
    }

//====================================================================================================================

    /**
     * @Route("/detail/{id}", name="detail")
     * @Template()
     */
    public function detailAction($id)
    {
        $serviceSystem = $this->get('ikaros_systemService');
        $servicePCB = $this->get('ikaros_pcbService');
        $servicePart = $this->get('ikaros_partService');

        $system = $serviceSystem->getActiveSystem($id);
        $pcb = $servicePCB->getActivePcbBySystemID_extended($id);
        $parts = $servicePart->getActivePartsBySystemID($id);

        return array('system' => $system, 'desk' => $pcb, 'parts' => $parts);
    }

//====================================================================================================================

    /**
     * @Route("/detailA/{id}", name="detailAdmin")
     * @Template()
     */
    public function detailAdminAction($id)
    {
        $serviceSystem = $this->get('ikaros_systemService');
        $servicePCB = $this->get('ikaros_pcbService');
        $servicePart = $this->get('ikaros_partService');

        $system = $serviceSystem->getSystem($id);
        $pcb = $servicePCB->getAllPcbBySystemID($id);
        $parts = $servicePart->getAllPartsBySystemID($id);

        return $this->render('BakalarkaIkarosBundle:Systems:detail.html.twig', array(
            'system' => $system, 'desk' => $pcb, 'parts' => $parts
        ));
    }
//====================================================================================================================

    /**
     * @Route("/newSystem", name="newSystem")
     * @Template()
     */
    public function newSystemAction()
    {
        $error = "";
        $serviceSystem = $this->get('ikaros_systemService');
        $envChoices = $serviceSystem->getEnvChoices();

        /*$system = new System();
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

            ->getForm();*/

        $systemForm = $this->createForm(new SystemForm(), new System(), array('envChoices' => $envChoices ,
            'mode' => "newSystem"));

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $systemForm->bind($request);
            if ($systemForm->isValid()) {
                $user = $this->get('security.context')->getToken()->getUser();
                try {
                    $em = $this->get('doctrine')->getManager();
                    $system = $systemForm->getData();
                    $system->setUserID($user);
                    $system->setEnvironment($systemForm['Environment']->getData());
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
            'form' => $systemForm->createView(), 'error' => $error,
        ));
    }
//====================================================================================================================

    /**
     * @Route("/detailSystem/{id}", name="detailSystem")
     * @Template()
     */
     public function detailSystemAction($id) {
        $serviceSystem = $this->get('ikaros_systemService');
        $envChoices = $serviceSystem->getEnvChoices();

        $system = $serviceSystem->getItem($id);

        $systemArray = $system->to_array();

        $systemForm = $this->createForm(new SystemForm(), $system, array('envChoices' => $envChoices ,
             'mode' => "editSystem", 'system' => $systemArray));

        return array('form' => $systemForm->createView(), 'idS' => $system->getIDSystem(),  'Title' => $system->getTitle());

     }

//====================================================================================================================

    /**
     * @Route("/editSystem/{id}", name="editSystem")
     * @Template()
     */
    public function editSystemAction($id) {
        $post = $this->get('request')->request;
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->sysForm;

        $em =  $this->getDoctrine()->getManager();

        $serviceSystem = $this->get('ikaros_systemService');
        $system = $serviceSystem->getItem($id);

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

//====================================================================================================================

    /**
     * @Route("/delSystem/{id}", name="delSystem")
     * @Template()
     */
    public function delSystemAction($id)
    {
        //$error = "";

        $em =  $this->getDoctrine()->getManager();

        $serviceSystem = $this->get('ikaros_systemService');
        $system = $serviceSystem->getItem($id);

        $status = $serviceSystem->setDeleteDate($system);
        if($status)
            return $this->redirect($this->generateUrl('mySystems'));

        $servicePCB = $this->get('ikaros_pcbService');
        $pcbs = $servicePCB->getActivePcbBySystemID($id);

        $servicePart = $this->get('ikaros_partService');
        $parts = $servicePart->getActiveParts($id);

        $status = $servicePart->setDeleteDateToParts($parts);
        if($status)
            return $this->redirect($this->generateUrl('mySystems'));

        $partsmt = $servicePCB->getPartsSmtBySystemID($id);
        $status = $servicePart->setDeleteDateToParts($partsmt);
        if($status)
            return $this->redirect($this->generateUrl('mySystems'));

        $status = $servicePCB->setDeleteDateToPcbs($pcbs);
        if($status)
            return $this->redirect($this->generateUrl('mySystems'));

        $em->flush();

        return $this->redirect($this->generateUrl('mySystems'));
    }
}

