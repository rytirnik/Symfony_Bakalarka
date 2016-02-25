<?php

namespace Bakalarka\IkarosBundle\Controller;

use Bakalarka\IkarosBundle\Entity\ConnectorGen;
use Bakalarka\IkarosBundle\Entity\Filter;
use Bakalarka\IkarosBundle\Entity\RotDevElaps;
use Bakalarka\IkarosBundle\Entity\Switches;
use Bakalarka\IkarosBundle\Entity\TubeWave;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

use Bakalarka\IkarosBundle\Entity\User;
use Bakalarka\IkarosBundle\Entity\Resistor;
use Bakalarka\IkarosBundle\Entity\Capacitor;
use Bakalarka\IkarosBundle\Entity\System;
use Bakalarka\IkarosBundle\Entity\Fuse;
use Bakalarka\IkarosBundle\Entity\Connections;
use Bakalarka\IkarosBundle\Entity\ConnectorSoc;

use Bakalarka\IkarosBundle\Forms\FuseForm;
use Bakalarka\IkarosBundle\Forms\ConnectionForm;
use Bakalarka\IkarosBundle\Forms\CapacitorForm;
use Bakalarka\IkarosBundle\Forms\ConnectorGenForm;
use Bakalarka\IkarosBundle\Forms\ConnectorSocForm;
use Bakalarka\IkarosBundle\Forms\FilterForm;
use Bakalarka\IkarosBundle\Forms\ResistorForm;
use Bakalarka\IkarosBundle\Forms\RotDevElapsForm;
use Bakalarka\IkarosBundle\Forms\SwitchForm;
use Bakalarka\IkarosBundle\Forms\TubeWaveForm;

class PartsController extends Controller
{


    /**
     * @Route("/newPart/{id}", name="newPart")
     * @Template()
     */
    public function newPartAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $em =  $this->getDoctrine()->getManager();
        $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
        $pcb = $RU->findOneBy(array('ID_PCB' => $id));

        $RU = $em->getRepository('BakalarkaIkarosBundle:System');
        $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));
        $sysEnv = $system->getEnvironment();
        $sysTemp = $system->getTemp();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, resQ.*
                        FROM
                       (SELECT res.*, q.Description
                       FROM Resistor res LEFT JOIN QualityResistor q ON (res.Quality = q.Value)) AS resQ
                       LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(resQ.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "rezistor"');
        $stmt->execute(array(':id' => $id));
        $resistors = $stmt->fetchAll();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, capQ.*
                        FROM
                       (SELECT cap.*, q.Description
                       FROM Capacitor cap LEFT JOIN QualityCapacitor q ON (cap.Quality = q.Value)) AS capQ
                       LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(capQ.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "kondenzátor"');
        $stmt->execute(array(':id' => $id));
        $capacitors = $stmt->fetchAll();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, fuse.*
                       FROM Fuse fuse LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(fuse.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "pojistka"');

        $stmt->execute(array(':id' => $id));
        $fuses = $stmt->fetchAll();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, conQ.*
                        FROM
                       (SELECT con.*, q.Description
                       FROM Connections con LEFT JOIN ConnectionType q ON (con.ConnectionType = q.Lamb)) AS conQ
                       LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(conQ.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "propojení"');
        $stmt->execute(array(':id' => $id));
        $connections = $stmt->fetchAll();


        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, conS.*
                       FROM ConnectorSoc conS LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(conS.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "konektor, soket"');
        $stmt->execute(array(':id' => $id));
        $conSoc = $stmt->fetchAll();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, conG.*
                       FROM ConnectorGen conG LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(conG.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "konektor, obecný"');
        $stmt->execute(array(':id' => $id));
        $conGen = $stmt->fetchAll();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, s.*
                       FROM Switches s LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(s.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "spínač"');
        $stmt->execute(array(':id' => $id));
        $switches = $stmt->fetchAll();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, f.*
                       FROM Filter f LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(f.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "filtr"');
        $stmt->execute(array(':id' => $id));
        $filters = $stmt->fetchAll();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, rot.*
                       FROM RotDevElaps rot LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(rot.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "měřič motohodin"');
        $stmt->execute(array(':id' => $id));
        $rotElaps = $stmt->fetchAll();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT p.*, tube.*
                       FROM TubeWave tube LEFT JOIN (SELECT part.*
                        FROM Part part LEFT JOIN PCB pcb ON (part.PCB_ID = pcb.ID_PCB)
                        WHERE pcb.ID_PCB = :id AND part.DeleteDate IS NULL AND pcb.DeleteDate IS NULL) AS p
                       ON(tube.ID_Part = p.ID_Part)
                       WHERE p.entity_type = "permaktron"');
        $stmt->execute(array(':id' => $id));
        $tubeWaves = $stmt->fetchAll();

        $serviceSystem = $this->get('ikaros_systemService');
        $envChoices = $serviceSystem->getEnvChoices();

//---Resistor form---------------------------------------------------------------------------

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM QualityResistor');
        $stmt->execute();
        $qr = $stmt->fetchAll();*/



        $serviceResistor = $this->get('ikaros_resistorService');
        $qr = $serviceResistor->getResQualityAll();
        $mat = $serviceResistor->getResMaterialAll();

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM MaterialResistor');
        $stmt->execute();
        $mat = $stmt->fetchAll();*/

        foreach($qr as $q) {
            $QualityChoices[$q['Value']] = $q['Description'];
        }

        foreach($mat as $m) {
            $MatChoices[$m['ResShortcut']] = $m['ResShortcut'];
        }

        /*$resistor = new Resistor();
        $formResistor = $this->createFormBuilder($resistor)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $sysEnv
            ))
            ->add('Label', 'text', array(
                'required' => true,
                'label' => 'Název',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Quality', 'choice', array(
                'label' => 'Kvalita',
                'choices' => $QualityChoices,
                'required' => true,
            ))
            ->add('Material', 'choice', array(
                'label' => 'Materiál',
                'choices' => $MatChoices,
                'required' => true,
            ))
            ->add('Type', 'text', array(
                'required' => false,
                'label' => 'Typ',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('CasePart', 'text', array(
                'required' => false,
                'label' => 'Pouzdro',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Value', 'integer', array(
                'required' => false,
                'label' => 'Hodnota [Ω]',
                'error_bubbling' => true,
            ))
            ->add('MaxPower', 'number', array(
                'required' => true,
                'label' => 'Maximální výkon [W]',
                'error_bubbling' => true,
            ))
            ->add('VoltageOperational', 'number', array(
                'required' => false,
                'label' => 'Provozní napětí [V]',
                'error_bubbling' => true,
            ))
            ->add('CurrentOperational', 'number', array(
                'required' => false,
                'label' => 'Provozní proud [A]',
                'error_bubbling' => true,
            ))
            ->add('DissipationPower', 'number', array(
                'required' => true,
                'label' => 'Ztrátový výkon [W]',
                'error_bubbling' => true,
            ))
            ->add('DPTemp', 'number', array(
                'required' => true,
                'label' => 'Oteplení ztrát. výkonem [°C]',
                'error_bubbling' => true,
                'data' => 0
            ))
            ->add('PassiveTemp', 'number', array(
                'required' => true,
                'label' => 'Pasivní oteplení [°C]',
                'error_bubbling' => true,
                'data' => 0
            ))
            ->add('Alternate', 'number', array(
                'required' => false,
                'label' => 'Střídavý proud [A]',
                'error_bubbling' => true,
            ))

            ->getForm();*/

        $formResistor = $this->createForm(new ResistorForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'qualityChoices' => $QualityChoices , 'matChoices' => $MatChoices));


        /*$request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $formResistor->bind($request);
            if ($formResistor->isValid()) {

                $resistor->setPCBID($pcb);
                try {
                    $em = $this->get('doctrine')->getManager();
                    $resistor->setTemp($sysTemp + $resistor->getDPTemp() + $resistor->getPassiveTemp());
                    $em->persist($resistor);
                    $em->flush();
                }
                catch (\Exception $e) {
                    echo $e;
                    
                    return $this->render('BakalarkaIkarosBundle:Parts:newPart.html.twig', array(
                        'formResistor' => $formResistor->createView(), 'resistors' => $resistors, 'pcb' => $pcb, 'e' => $e
                    ));
                }
                return $this->redirect($this->generateUrl('mySystems'));
            }
        }*/

//---Capacitor form---------------------------------------------------------------------------

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM QualityCapacitor');
        $stmt->execute();
        $qc = $stmt->fetchAll();

        $stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM MaterialCapacitor');
        $stmt->execute();
        $mc = $stmt->fetchAll();*/

        $serviceCapacitor = $this->get('ikaros_capacitorService');
        $qc = $serviceCapacitor->getCapQualityAll();
        $mc = $serviceCapacitor->getCapMaterialAll();

        foreach($qc as $q) {
            $QualityChoicesC[$q['Value']] = $q['Description'];
        }

        foreach($mc as $m) {
            $MatChoicesC[$m['CapShortcut']] = $m['CapShortcut'];
        }

        /*$capacitor = new Capacitor();
        $formCapacitor = $this->createFormBuilder($capacitor)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $sysEnv
            ))
            ->add('Label', 'text', array(
                'required' => true,
                'label' => 'Název',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Quality', 'choice', array(
                'label' => 'Kvalita',
                'choices' => $QualityChoicesC,
                'required' => true,
            ))
            ->add('Material', 'choice', array(
                'label' => 'Materiál',
                'choices' => $MatChoicesC,
                'required' => true,
            ))
            ->add('Type', 'text', array(
                'required' => false,
                'label' => 'Typ',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('CasePart', 'text', array(
                'required' => false,
                'label' => 'Pouzdro',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Value', 'number', array(
                'required' => true,
                'label' => 'Hodnota [μF]',
                'error_bubbling' => true,
            ))
            ->add('VoltageMax', 'number', array(
                'required' => true,
                'label' => 'Maximální napětí [V]',
                'error_bubbling' => true,
            ))
            ->add('VoltageOperational', 'number', array(
                'required' => true,
                'label' => 'Provozní napětí [V]',
                'error_bubbling' => true,
            ))
            ->add('VoltageDC', 'number', array(
                'required' => false,
                'label' => 'Napětí DC [V]',
                'error_bubbling' => true,
            ))
            ->add('VoltageAC', 'number', array(
                'required' => false,
                'label' => 'Napětí AC [V]',
                'error_bubbling' => true,
            ))
            ->add('SerialResistor', 'number', array(
                'required' => false,
                'label' => 'Odpor v sérii tantaly [Ω]',
                'error_bubbling' => true,
            ))
            ->add('PassiveTemp', 'integer', array(
                'required' => true,
                'label' => 'Pasivní oteplení [°C]',
                'error_bubbling' => true,
                'data' => 0
            ))

            ->getForm();*/

        $formCapacitor = $this->createForm(new CapacitorForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'qualityChoicesC' => $QualityChoicesC , 'matChoicesC' => $MatChoicesC));


//---Fuse form---------------------------------------------------------------------------
        /*$fuse = new Fuse();
        $formFuse = $this->createFormBuilder($fuse)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $sysEnv
            ))
            ->add('Label', 'text', array(
                'required' => true,
                'label' => 'Název',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Type', 'text', array(
                'required' => false,
                'label' => 'Typ',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('CasePart', 'text', array(
                'required' => false,
                'label' => 'Pouzdro',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Value', 'number', array(
                'required' => false,
                'label' => 'Hodnota [A]',
                'error_bubbling' => true,
            ))
            ->getForm();*/

        $service = $this->get('ikaros_systemService');
        $envChoices = $service->getEnvChoices();

        $formFuse = $this->createForm(new FuseForm(), array(), array('envChoices' => $envChoices , 'sysEnv' => $sysEnv));

//---Connectino form---------------------------------------------------------------------------

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM ConnectionType');
        $stmt->execute();
        $conType = $stmt->fetchAll();*/

        $serviceConnection = $this->get('ikaros_connectionService');
        $conType = $serviceConnection->getConTypeAll();

        foreach($conType as $m) {
            $conTypeChoices[$m['Lamb']] = $m['Description'];
        }

        /*$connection = new Connections();
        $formConnection = $this->createFormBuilder($connection)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $sysEnv
            ))
            ->add('ConnectionType', 'choice', array(
                'required' => true,
                'label' => 'Popis',
                'choices' => $conTypeChoices
            ))
            ->add('Label', 'text', array(
                'required' => true,
                'label' => 'Název',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Type', 'text', array(
                'required' => false,
                'label' => 'Typ',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('CasePart', 'text', array(
                'required' => false,
                'label' => 'Pouzdro',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->getForm();*/

        $formConnection = $this->createForm(new ConnectionForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'conTypeChoices' => $conTypeChoices));

//---Connector - socket form---------------------------------------------------------------------------
        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM ConnectorSocType');
        $stmt->execute();
        $conSocType = $stmt->fetchAll();*/

        $serviceConnector = $this->get('ikaros_connectorService');
        $conSocType = $serviceConnector->getConSocTypeAll();

        foreach($conSocType as $m) {
            $conSocTypeChoices[$m['Description']] = $m['Description'];
        }

        /*$connectorSoc = new ConnectorSoc();
        $formConSoc = $this->createFormBuilder($connectorSoc)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $sysEnv
            ))
            ->add('ConnectorType', 'choice', array(
                'required' => true,
                'label' => 'Popis',
                'choices' => $conSocTypeChoices
            ))
            ->add('Quality', 'choice', array(
                'required' => true,
                'label' => 'Kvalita',
                'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower")
            ))
            ->add('Label', 'text', array(
                'required' => true,
                'label' => 'Název',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Type', 'text', array(
                'required' => false,
                'label' => 'Typ',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('CasePart', 'text', array(
                'required' => false,
                'label' => 'Pouzdro',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('ActivePins', 'integer', array(
                'required' => true,
                'label' => 'Aktivní piny',
                'error_bubbling' => true,
                'attr' => array('min'=>1)
            ))
            ->getForm();*/

        $formConSoc = $this->createForm(new ConnectorSocForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'conSocTypeChoices' => $conSocTypeChoices));


//---Connector - general form---------------------------------------------------------------------------

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM ConnectorGenType');
        $stmt->execute();
        $conGenType = $stmt->fetchAll();*/


        $conGenType = $serviceConnector->getConGenTypeAll();

        foreach($conGenType as $m) {
            $conGenTypeChoices[$m['Description']] = $m['Description'];
        }

        /*$connectorGen = new ConnectorGen();
        $formConGen = $this->createFormBuilder($connectorGen)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $sysEnv
            ))
            ->add('ConnectorType', 'choice', array(
                'required' => true,
                'label' => 'Popis',
                'choices' => $conGenTypeChoices
            ))
            ->add('Quality', 'choice', array(
                'required' => true,
                'label' => 'Kvalita',
                'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower")
            ))
            ->add('Label', 'text', array(
                'required' => true,
                'label' => 'Název',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Type', 'text', array(
                'required' => false,
                'label' => 'Typ',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('CasePart', 'text', array(
                'required' => false,
                'label' => 'Pouzdro',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('ContactCnt', 'integer', array(
                'required' => true,
                'label' => 'Počet kontaktů',
                'error_bubbling' => true,
                'attr' => array('min'=>0)
            ))
            ->add('CurrentContact', 'number', array(
                'required' => true,
                'label' => 'Proud na kontakt [A]',
                'error_bubbling' => true
            ))
            ->add('MatingFactor', 'integer', array(
                'required' => true,
                'label' => 'Počet spoj/rozp za 1000h',
                'error_bubbling' => true
            ))
            ->add('PassiveTemp', 'integer', array(
                'required' => true,
                'label' => 'Pasivní oteplení  [°C]',
                'error_bubbling' => true
            ))
            ->getForm();*/

        $formConGen = $this->createForm(new ConnectorGenForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'conGenTypeChoices' => $conGenTypeChoices));

//---Switch form---------------------------------------------------------------------------

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM SwitchType');
        $stmt->execute();
        $swType = $stmt->fetchAll();*/

        $serviceSwitch = $this->get('ikaros_switchService');
        $swType = $serviceSwitch->getSwitchTypeAll();

        foreach($swType as $m) {
            $swTypeChoices[$m['Description']] = $m['Description'];
        }

        /*$switch = new Switches();
        $formSwitch = $this->createFormBuilder($switch)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $sysEnv
            ))
            ->add('Label', 'text', array(
                'required' => true,
                'label' => 'Název',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Type', 'text', array(
                'required' => false,
                'label' => 'Typ',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('CasePart', 'text', array(
                'required' => false,
                'label' => 'Pouzdro',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('SwitchType', 'choice', array(
                'required' => true,
                'label' => 'Popis',
                'choices' => $swTypeChoices
            ))
            ->add('Quality', 'choice', array(
                'required' => true,
                'label' => 'Kvalita',
                'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower")
            ))
            ->add('LoadType', 'choice', array(
                'required' => true,
                'label' => 'Typ zátěže',
                'error_bubbling' => true,
                'choices' => array("Resistive" => "Resistive", "Inductive" => "Inductive", "Lamp" => "Lamp")
            ))
            ->add('ContactCnt', 'integer', array(
                'required' => true,
                'label' => 'Počet kontaktů',
                'error_bubbling' => true,
                'attr' => array('min'=>0)
            ))
            ->add('OperatingCurrent', 'number', array(
                'required' => true,
                'label' => 'Pracovní proud [A]',
                'error_bubbling' => true
            ))
            ->add('RatedResistiveCurrent', 'integer', array(
                'required' => true,
                'label' => 'Maximální proud [A]',
                'error_bubbling' => true
            ))

            ->getForm();*/

        $formSwitch = $this->createForm(new SwitchForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'swTypeChoices' => $swTypeChoices));

//---Filter form---------------------------------------------------------------------------

        /*$stmt = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('SELECT *
                        FROM FilterType');
        $stmt->execute();
        $filterType = $stmt->fetchAll();*/

        $serviceFilter = $this->get('ikaros_filterService');
        $filterType = $serviceFilter->getFilterTypeAll();

        foreach($filterType as $m) {
            $filterTypeChoices[$m['Description']] = $m['Description'];
        }

        /*$filter = new Filter();
        $formFilter = $this->createFormBuilder($filter)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $sysEnv
            ))
            ->add('FilterType', 'choice', array(
                'required' => true,
                'label' => 'Popis',
                'choices' => $filterTypeChoices
            ))
            ->add('Quality', 'choice', array(
                'required' => true,
                'label' => 'Kvalita',
                'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower")
            ))
            ->add('Label', 'text', array(
                'required' => true,
                'label' => 'Název',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Type', 'text', array(
                'required' => false,
                'label' => 'Typ',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('CasePart', 'text', array(
                'required' => false,
                'label' => 'Pouzdro',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->getForm();*/

        $formFilter = $this->createForm(new FilterForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'filterTypeChoices' => $filterTypeChoices));

//---Rot dev elaps form---------------------------------------------------------------------------

        /*$rotEl = new RotDevElaps();
        $formRotElaps = $this->createFormBuilder($rotEl)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $sysEnv
            ))
            ->add('DevType', 'choice', array(
                'required' => true,
                'label' => 'Popis',
                'choices' => array("A.C." => "A.C.", "Inverter Driven" => "Inverter Driven", "Commutator D.C." => "Commutator D.C.")
            ))
            ->add('Label', 'text', array(
                'required' => true,
                'label' => 'Název',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Type', 'text', array(
                'required' => false,
                'label' => 'Typ',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('CasePart', 'text', array(
                'required' => false,
                'label' => 'Pouzdro',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('TempOperational', 'integer', array(
                'required' => true,
                'label' => 'Provozní teplota [°C]',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('TempMax', 'integer', array(
                'required' => true,
                'label' => 'Maximální teplota [°C]',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->getForm();*/

        $formRotElaps = $this->createForm(new RotDevElapsForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv));

//---TubeWave form---------------------------------------------------------------------------

        /*$tubeWave = new TubeWave();
        $formTubeWave= $this->createFormBuilder($tubeWave)
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $sysEnv
            ))
            ->add('Label', 'text', array(
                'required' => true,
                'label' => 'Název',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Type', 'text', array(
                'required' => false,
                'label' => 'Typ',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('CasePart', 'text', array(
                'required' => false,
                'label' => 'Pouzdro',
                'error_bubbling' => true,
                'max_length' => 64,
            ))
            ->add('Power', 'integer', array(
                'required' => true,
                'label' => 'Výkon (10-40000) [W]',
                'error_bubbling' => true,
                'max_length' => 64,
                'attr' => array('min'=>10, 'max'=>40000)
            ))
            ->add('Frequency', 'number', array(
                'required' => true,
                'label' => 'Frekvence (0.1-18) [GHz]',
                'error_bubbling' => true,
                'max_length' => 64,
                'attr' => array('min'=>0.1, 'max'=>18)
            ))
            ->getForm();*/

        $formTubeWave = $this->createForm(new TubeWaveForm(), array(),array('envChoices' => $envChoices , 'sysEnv' => $sysEnv));

        return $this->render('BakalarkaIkarosBundle:Parts:newPart.html.twig', array(
            'system' => $system,  'pcb' => $pcb, 'e' => "",
            'formResistor' => $formResistor->createView(), 'resistors' => $resistors, 'matRes' => $mat,
            'formCapacitor' => $formCapacitor->createView(), 'capacitors' => $capacitors, 'matCap' => $mc,
            'formFuse' => $formFuse->createView(), 'fuses' => $fuses,
            'formConnection' => $formConnection->createView(), 'connections' => $connections,
            'formConSoc' => $formConSoc->createView(), 'conSoc' => $conSoc,
            'formConGen' => $formConGen->createView(), 'conGen' => $conGen,
            'formSwitch' => $formSwitch->createView(), 'switches' => $switches,
            'formFilter' => $formFilter->createView(), 'filters' => $filters,
            'formRotElaps' => $formRotElaps->createView(), 'rotElaps' => $rotElaps,
            'formTubeWave' => $formTubeWave->createView(), 'tubeWaves' => $tubeWaves,
        ));

    }

//====================================================================================================================
    /**
     * @Route("/newResistor", name="newResistorAjax")
     * @Template()
     */
    public function newResistorAjaxAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->resistorForm;

        $res = new Resistor();
        $res->setParams($obj);

        $service = $this->get('ikaros_resistorService');

        $e = $service->setLams($res, $id);

        $qualR = $service->getResQuality($res->getQuality());

        if($e != "")
            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );

        return new Response(
            json_encode(array(
                'Label' => $res->getLabel(),
                'Lam' => $res->getLam(),
                'Value' => $res->getValue(),
                'MaxPower' => $res->getMaxPower(),
                'DissipationPower' => $res->getDissipationPower(),
                'DPTemp' => $res->getDPTemp(),
                'PassiveTemp' => $res->getPassiveTemp(),
                'Material' => $res->getMaterial(),
                'Quality' => $qualR,
                'Environment' => $res->getEnvironment(),
                'idP' => $res->getIDPart()
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }

//====================================================================================================================
    /**
     * @Route("/newCapacitor", name="newCapacitor")
     * @Template()
     */
    public function newCapacitorAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->capacitorForm;

        $cap = new Capacitor();
        $cap->setParams($obj);

        $service = $this->get('ikaros_capacitorService');

        $e = $service->setLams($cap, $id);

        $qualC = $service->getCapQuality($cap->getQuality());

        if($e != "")
            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );

        return new Response(
            json_encode(array(
                'Label' => $cap->getLabel(),
                'Lam' => $cap->getLam(),
                'Value' => $cap->getValue(),
                'VoltageMax' => $cap->getVoltageMax(),
                'VoltageOperational' => $cap->getVoltageOperational(),
                'PassiveTemp' => $cap->getPassiveTemp(),
                'Material' => $cap->getMaterial(),
                'Quality' => $qualC,
                'Environment' => $cap->getEnvironment(),
                'idP' => $cap->getIDPart()
            )),
              200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );


    }

//====================================================================================================================
    /**
     * @Route("/newFuse", name="newFuse")
     * @Template()
     */
    public function newFuseAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');

        $formData = $post->get('formData');
        $objF = json_decode($formData);
        $obj = $objF->fuseForm;

        $fuse = new Fuse();
        $fuse->setParams($obj);

        $service = $this->get('ikaros_fuseService');
        $lambda = $service->lamFuse($fuse);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $fuse, $id);
        if($e != "")
            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );

        return new Response(
            json_encode(array(
                'Label' => $fuse->getLabel(),
                'Lam' => $fuse->getLam(),
                'Value' => $fuse->getValue(),
                'Type' => $fuse->getType(),
                'CasePart' => $fuse->getCasePart(),
                'Environment' => $fuse->getEnvironment(),
                'idP' => $fuse->getIDPart()
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );

    }

//====================================================================================================================
    /**
     * @Route("/newConnection", name="newConnection")
     * @Template()
     */
    public function newConnectionAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->connectionForm;

        $con = new Connections();
        $con->setParams($obj);

        $service = $this->get('ikaros_connectionService');
        $lambda = $service->lamConnection($con);

        $conType = $service->getConType($con->getConnectionType());

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $con, $id);
        if($e != "")
            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );

        return new Response(
            json_encode(array(
                'Label' => $con->getLabel(),
                'Lam' => $con->getLam(),
                'ConnectionType' => $conType,
                'Type' => $con->getType(),
                'CasePart' => $con->getCasePart(),
                'Environment' => $con->getEnvironment(),
                'idP' => $con->getIDPart()
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );

    }

//====================================================================================================================
    /**
     * @Route("/newConSoc", name="newConSoc")
     * @Template()
     */
    public function newConSocAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->connectorSocForm;

        $conSoc = new ConnectorSoc();
        $conSoc->setParams($obj);

        $service = $this->get('ikaros_connectorService');
        $lambda = $service->lamConSoc($conSoc);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $conSoc, $id);
        if($e != "")

            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );
        //}

        return new Response(
            json_encode(array(
                'Label' => $conSoc->getLabel(),
                'Lam' => $conSoc->getLam(),
                'ConnectorType' => $conSoc->getConnectorType(),
                'Quality' => $conSoc->getQuality(),
                'CasePart' => $conSoc->getCasePart(),
                'Type' => $conSoc->getType(),
                'ActivePins' => $conSoc->getActivePins(),
                'Environment' => $conSoc->getEnvironment(),
                'idP' => $conSoc->getIDPart()
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }

//====================================================================================================================
    /**
     * @Route("/newConGen", name="newConGen")
     * @Template()
     */
    public function newConGenAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->connectorGenForm;

        $conGen = new ConnectorGen();
        $conGen->setParams($obj);

        $service = $this->get('ikaros_connectorService');
        $e = $service->setLamsConGen($conGen, $id);
        if($e != "")
                return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );
        //}

        return new Response(
            json_encode(array(
                'Label' => $conGen->getLabel(),
                'Lam' => $conGen->getLam(),
                'ConnectorType' => $conGen->getConnectorType(),
                'Quality' => $conGen->getQuality(),
                'Environment' => $conGen->getEnvironment(),
                'MatingFactor' => $conGen->getMatingFactor(),
                'ContactCnt' => $conGen->getContactCnt(),
                'PassiveTemp' => $conGen->getPassiveTemp(),
                'CurrentContact' => $conGen->getCurrentContact(),
                'idP' => $conGen->getIDPart()
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }


//====================================================================================================================
    /**
     * @Route("/newSwitch", name="newSwitch")
     * @Template()
     */
    public function newSwitchAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->switchForm;

        $switch = new Switches();
        $switch->setParams($obj);

        //$lambda = $this->lamSwitch($switch);
        $service = $this->get('ikaros_switchService');
        $lambda = $service->lamSwitch($switch);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $switch, $id);
        if($e != "")
            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );
        //}

        return new Response(
            json_encode(array(
                'Label' => $switch->getLabel(),
                'Lam' => $switch->getLam(),
                'SwitchType' => $switch->getSwitchType(),
                'Quality' => $switch->getQuality(),
                'Environment' => $switch->getEnvironment(),
                'LoadType' => $switch->getLoadType(),
                'ContactCnt' => $switch->getContactCnt(),
                'OperatingCurrent' => $switch->getOperatingCurrent(),
                'RatedResistiveCurrent' => $switch->getRatedResistiveCurrent(),
                'idP' => $switch->getIDPart()
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }

//====================================================================================================================
    /**
     * @Route("/newFilter", name="newFilter")
     * @Template()
     */
    public function newFilterAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->filterForm;

        $filter = new Filter();
        $filter->setParams($obj);

        $service = $this->get('ikaros_filterService');
        $lambda = $service->lamFilter($filter);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $filter, $id);
        if($e != "")
            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );
        //}

        return new Response(
            json_encode(array(
                'Label' => $filter->getLabel(),
                'Lam' => $filter->getLam(),
                'FilterType' => $filter->getFilterType(),
                'Type' => $filter->getType(),
                'CasePart' => $filter->getCasePart(),
                'Environment' => $filter->getEnvironment(),
                'Quality' => $filter->getQuality(),
                'idP' => $filter->getIDPart()
            )),
            //HTTP status
            200,
            //headers
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );

    }

//====================================================================================================================
    /**
     * @Route("/newRotElaps", name="newRotElaps")
     * @Template()
     */
    public function newRotElapsAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->rotDevElapsForm;

        $rotElaps = new RotDevElaps();
        $rotElaps->setParams($obj);

        $service = $this->get('ikaros_rotDevElapsService');
        $lambda = $service->lamRotElaps($rotElaps);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $rotElaps, $id);
        if($e != "")
            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );
        //}

        return new Response(
            json_encode(array(
                'Label' => $rotElaps->getLabel(),
                'Lam' => $rotElaps->getLam(),
                'DevType' => $rotElaps->getDevType(),
                'Type' => $rotElaps->getType(),
                'CasePart' => $rotElaps->getCasePart(),
                'Environment' => $rotElaps->getEnvironment(),
                'TempMax' => $rotElaps->getTempMax(),
                'TempOperational' => $rotElaps->getTempOperational(),
                'idP' => $rotElaps->getIDPart()
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );

    }

//====================================================================================================================
    /**
     * @Route("/newTubeWave", name="newTubeWave")
     * @Template()
     */
    public function newTubeWaveAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->tubeWaveForm;

        $tubeWave = new TubeWave();
        $tubeWave->setParams($obj);

        $service = $this->get('ikaros_tubeWaveService');
        $lambda = $service->lamTubeWave($tubeWave);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $tubeWave, $id);
        if($e != "")
            return new Response(
                json_encode(array(
                    'e' => $e
                )),
                400,
                array(
                    'Content-Type' => 'application/json; charset=utf-8'
                )
            );

        return new Response(
            json_encode(array(
                'Label' => $tubeWave->getLabel(),
                'Lam' => $tubeWave->getLam(),
                'Type' => $tubeWave->getType(),
                'CasePart' => $tubeWave->getCasePart(),
                'Environment' => $tubeWave->getEnvironment(),
                'Power' => $tubeWave->getPower(),
                'Frequency' => $tubeWave->getFrequency(),
                'idP' => $tubeWave->getIDPart()
            )),
            //HTTP status
            200,
            //headers
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );

    }

//====================================================================================================================
    /**
     * @Route("/detailPart/{id}", name="detailPart")
     * @Template()
     */
    public function detailPartAction($id) {

        $servicePart = $this->get('ikaros_partService');
        $type = $servicePart->getType($id);

        $em =  $this->getDoctrine()->getManager();

        $serviceSystem = $this->get('ikaros_systemService');
        $envChoices = $serviceSystem->getEnvChoices();

        switch($type) {
            case "rezistor":
                //$RU = $em->getRepository('BakalarkaIkarosBundle:Resistor');
                //$res = $RU->findOneBy(array('ID_Part' => $id));

                $serviceRes = $this->get('ikaros_resistorService');

                $res = $serviceRes->getItem($id);
                $qr = $serviceRes->getResQualityAll();
                $mat = $serviceRes->getResMaterialAll();

                foreach($qr as $q) {
                    $QualityChoices[$q['Value']] = $q['Description'];
                }

                foreach($mat as $m) {
                    $MatChoices[$m['ResShortcut']] = $m['ResShortcut'];
                }

                //$resistor = new Resistor();
                $formResistor = $this->createFormBuilder($res)
                    ->add('Environment', 'choice', array(
                        'label' => 'Prostředí',
                        'choices' => $envChoices,
                        'required' => true,
                        'data' => $res->getEnvironment()
                    ))
                    ->add('Label', 'text', array(
                        'required' => true,
                        'label' => 'Název',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $res->getLabel()
                    ))
                    ->add('Quality', 'choice', array(
                        'label' => 'Kvalita',
                        'choices' => $QualityChoices,
                        'required' => true,
                        'data' => $res->getQuality()
                    ))
                    ->add('Material', 'choice', array(
                        'label' => 'Materiál',
                        'choices' => $MatChoices,
                        'required' => true,
                        'data' => $res->getMaterial()
                    ))
                    ->add('Type', 'text', array(
                        'required' => false,
                        'label' => 'Typ',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $res->getType()
                    ))
                    ->add('CasePart', 'text', array(
                        'required' => false,
                        'label' => 'Pouzdro',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $res->getCasePart()
                    ))
                    ->add('Value', 'integer', array(
                        'required' => false,
                        'label' => 'Hodnota [Ω]',
                        'error_bubbling' => true,
                        'data' => $res->getValue()
                    ))
                    ->add('MaxPower', 'number', array(
                        'required' => true,
                        'label' => 'Maximální výkon [W]',
                        'error_bubbling' => true,
                        'data' => $res->getMaxPower()
                    ))
                    ->add('VoltageOperational', 'number', array(
                        'required' => false,
                        'label' => 'Provozní napětí [V]',
                        'error_bubbling' => true,
                        'data' => $res->getVoltageOperational()
                    ))
                    ->add('CurrentOperational', 'number', array(
                        'required' => false,
                        'label' => 'Provozní proud [A]',
                        'error_bubbling' => true,
                        'data' => $res->getCurrentOperational()
                    ))
                    ->add('DissipationPower', 'number', array(
                        'required' => true,
                        'label' => 'Ztrátový výkon [W]',
                        'error_bubbling' => true,
                        'data' => $res->getDissipationPower()
                    ))
                    ->add('DPTemp', 'number', array(
                        'required' => true,
                        'label' => 'Oteplení ztrát. výkonem [°C]',
                        'error_bubbling' => true,
                        'data' => $res->getDPTemp()
                    ))
                    ->add('PassiveTemp', 'number', array(
                        'required' => true,
                        'label' => 'Pasivní oteplení [°C]',
                        'error_bubbling' => true,
                        'data' => $res->getPassiveTemp()
                    ))
                    ->add('Alternate', 'number', array(
                        'required' => false,
                        'label' => 'Střídavý proud [A]',
                        'error_bubbling' => true,
                        'data' => $res->getAlternate()
                    ))

                    ->getForm();

                return array('type'=> $type, 'formResistor' => $formResistor->createView(),'IDPart' => $res->getIDPart(), 'Lam' => $res->getLam(), 'Label' => $res->getLabel());

            case 'pojistka':
                $RU = $em->getRepository('BakalarkaIkarosBundle:Fuse');
                $fuse = $RU->findOneBy(array('ID_Part' => $id));

                $formFuse = $this->createFormBuilder($fuse)
                    ->add('Environment', 'choice', array(
                        'label' => 'Prostředí',
                        'choices' => $envChoices,
                        'required' => true,
                        'data' => $fuse->getEnvironment()
                    ))
                    ->add('Label', 'text', array(
                        'required' => true,
                        'label' => 'Název',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $fuse->getLabel()
                    ))
                    ->add('Type', 'text', array(
                        'required' => false,
                        'label' => 'Typ',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $fuse->getType()
                    ))
                    ->add('CasePart', 'text', array(
                        'required' => false,
                        'label' => 'Pouzdro',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $fuse->getCasePart()
                    ))
                    ->add('Value', 'number', array(
                        'required' => false,
                        'label' => 'Hodnota [A]',
                        'error_bubbling' => true,
                        'data' => $fuse->getValue()
                    ))
                    ->getForm();
                return array('type'=> $type, 'formFuse' => $formFuse->createView(),'IDPart' => $fuse->getIDPart(), 'Label' => $fuse->getLabel(), 'Lam' => $fuse->getLam());

            case 'kondenzátor':
                //$RU = $em->getRepository('BakalarkaIkarosBundle:Capacitor');
                //$capacitor = $RU->findOneBy(array('ID_Part' => $id));

                $serviceCap = $this->get('ikaros_capacitorService');

                $capacitor = $serviceCap->getItem($id);
                $qc = $serviceCap->getCapQualityAll();
                $mc = $serviceCap->getCapMaterialAll();

                foreach($qc as $q) {
                    $QualityChoicesC[$q['Value']] = $q['Description'];
                }

                foreach($mc as $m) {
                    $MatChoicesC[$m['CapShortcut']] = $m['CapShortcut'];
                }

                $formCapacitor = $this->createFormBuilder($capacitor)
                    ->add('Environment', 'choice', array(
                        'label' => 'Prostředí',
                        'choices' => $envChoices,
                        'required' => true,
                        'data' => $capacitor->getEnvironment()
                    ))
                    ->add('Label', 'text', array(
                        'required' => true,
                        'label' => 'Název',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $capacitor->getLabel()
                    ))
                    ->add('Quality', 'choice', array(
                        'label' => 'Kvalita',
                        'choices' => $QualityChoicesC,
                        'required' => true,
                        'data' => $capacitor->getQuality()
                    ))
                    ->add('Material', 'choice', array(
                        'label' => 'Materiál',
                        'choices' => $MatChoicesC,
                        'required' => true,
                        'data' => $capacitor->getMaterial()
                    ))
                    ->add('Type', 'text', array(
                        'required' => false,
                        'label' => 'Typ',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $capacitor->getType()
                    ))
                    ->add('CasePart', 'text', array(
                        'required' => false,
                        'label' => 'Pouzdro',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $capacitor->getCasePart()
                    ))
                    ->add('Value', 'integer', array(
                        'required' => true,
                        'label' => 'Hodnota [μF]',
                        'error_bubbling' => true,
                        'data' => $capacitor->getValue()
                    ))
                    ->add('VoltageMax', 'number', array(
                        'required' => true,
                        'label' => 'Maximální napětí [V]',
                        'error_bubbling' => true,
                        'data' => $capacitor->getVoltageMax()
                    ))
                    ->add('VoltageOperational', 'number', array(
                        'required' => true,
                        'label' => 'Provozní napětí [V]',
                        'error_bubbling' => true,
                        'data' => $capacitor->getVoltageOperational()
                    ))
                    ->add('VoltageDC', 'number', array(
                        'required' => false,
                        'label' => 'Napětí DC [V]',
                        'error_bubbling' => true,
                        'data' => $capacitor->getVoltageDC()
                    ))
                    ->add('VoltageAC', 'number', array(
                        'required' => false,
                        'label' => 'Napětí AC [V]',
                        'error_bubbling' => true,
                        'data' => $capacitor->getVoltageAC()
                    ))
                    ->add('SerialResistor', 'number', array(
                        'required' => false,
                        'label' => 'Odpor v sérii tantaly [Ω]',
                        'error_bubbling' => true,
                        'data' => $capacitor->getSerialResistor()
                    ))
                    ->add('PassiveTemp', 'integer', array(
                        'required' => true,
                        'label' => 'Pasivní oteplení [°C]',
                        'error_bubbling' => true,
                        'data' => $capacitor->getPassiveTemp()
                    ))

                    ->getForm();
                return array('type'=> $type, 'formCapacitor' => $formCapacitor->createView(),'IDPart' => $capacitor->getIDPart(), 'Label' => $capacitor->getLabel(), 'Lam' => $capacitor->getLam());

            case 'propojení':
                $serviceConnection = $this->get('ikaros_connectionService');
                $con = $serviceConnection->getItem($id);
                $conType = $serviceConnection->getConTypeAll();

                foreach($conType as $m) {
                    $conTypeChoices[$m['Lamb']] = $m['Description'];
                }

                //$RU = $em->getRepository('BakalarkaIkarosBundle:Connections');
                //$con = $RU->findOneBy(array('ID_Part' => $id));

                $formConnection = $this->createFormBuilder($con)
                    ->add('Environment', 'choice', array(
                        'label' => 'Prostředí',
                        'choices' => $envChoices,
                        'required' => true,
                        'data' => $con->getEnvironment()
                    ))
                    ->add('ConnectionType', 'choice', array(
                        'required' => true,
                        'label' => 'Popis',
                        'choices' => $conTypeChoices,
                        'data' => $con->getConnectionType()
                    ))
                    ->add('Label', 'text', array(
                        'required' => true,
                        'label' => 'Název',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $con->getLabel()
                    ))
                    ->add('Type', 'text', array(
                        'required' => false,
                        'label' => 'Typ',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $con->getType()
                    ))
                    ->add('CasePart', 'text', array(
                        'required' => false,
                        'label' => 'Pouzdro',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $con->getCasePart()
                    ))
                    ->getForm();
                return array('type'=> $type, 'formConnection' => $formConnection->createView(),'IDPart' => $con->getIDPart(), 'Label' => $con->getLabel(), 'Lam' => $con->getLam());

            case 'konektor, soket':
                $serviceConSoc = $this->get('ikaros_connectorService');

                $connectorSoc = $serviceConSoc->getItemSoc($id);
                $conSocType = $serviceConSoc->getConSocTypeAll();

                foreach($conSocType as $m) {
                    $conSocTypeChoices[$m['Description']] = $m['Description'];
                }

                //$RU = $em->getRepository('BakalarkaIkarosBundle:ConnectorSoc');
                //$connectorSoc = $RU->findOneBy(array('ID_Part' => $id));

                $formConSoc = $this->createFormBuilder($connectorSoc)
                    ->add('Environment', 'choice', array(
                        'label' => 'Prostředí',
                        'choices' => $envChoices,
                        'required' => true,
                        'data' => $connectorSoc->getEnvironment()
                    ))
                    ->add('ConnectorType', 'choice', array(
                        'required' => true,
                        'label' => 'Popis',
                        'choices' => $conSocTypeChoices,
                        'data' => $connectorSoc->getConnectorType()
                    ))
                    ->add('Quality', 'choice', array(
                        'required' => true,
                        'label' => 'Kvalita',
                        'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower"),
                        'data' => $connectorSoc->getQuality()
                    ))
                    ->add('Label', 'text', array(
                        'required' => true,
                        'label' => 'Název',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $connectorSoc->getLabel()
                    ))
                    ->add('Type', 'text', array(
                        'required' => false,
                        'label' => 'Typ',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $connectorSoc->getType()
                    ))
                    ->add('CasePart', 'text', array(
                        'required' => false,
                        'label' => 'Pouzdro',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $connectorSoc->getCasePart()
                    ))
                    ->add('ActivePins', 'integer', array(
                        'required' => true,
                        'label' => 'Aktivní piny',
                        'error_bubbling' => true,
                        'attr' => array('min'=>1),
                        'data' => $connectorSoc->getActivePins()
                    ))
                    ->getForm();

                return array('type'=> $type, 'formConSoc' => $formConSoc->createView(),
                    'IDPart' => $connectorSoc->getIDPart(), 'Label' => $connectorSoc->getLabel(), 'Lam' => $connectorSoc->getLam());

            case 'konektor, obecný':
                $serviceConGen = $this->get('ikaros_connectorService');

                $connectorGen = $serviceConGen->getItemGen($id);
                $conGenType = $serviceConGen->getConGenTypeAll();

                foreach($conGenType as $m) {
                    $conGenTypeChoices[$m['Description']] = $m['Description'];
                }

                //$RU = $em->getRepository('BakalarkaIkarosBundle:ConnectorGen');
                //$connectorGen = $RU->findOneBy(array('ID_Part' => $id));

                $formConGen = $this->createFormBuilder($connectorGen)
                    ->add('Environment', 'choice', array(
                        'label' => 'Prostředí',
                        'choices' => $envChoices,
                        'required' => true,
                        'data' => $connectorGen->getEnvironment()
                    ))
                    ->add('ConnectorType', 'choice', array(
                        'required' => true,
                        'label' => 'Popis',
                        'choices' => $conGenTypeChoices,
                        'data' => $connectorGen->getConnectorType()
                    ))
                    ->add('Quality', 'choice', array(
                        'required' => true,
                        'label' => 'Kvalita',
                        'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower"),
                        'data' => $connectorGen->getQuality()
                    ))
                    ->add('Label', 'text', array(
                        'required' => true,
                        'label' => 'Název',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $connectorGen->getLabel()
                    ))
                    ->add('Type', 'text', array(
                        'required' => false,
                        'label' => 'Typ',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $connectorGen->getType()
                    ))
                    ->add('CasePart', 'text', array(
                        'required' => false,
                        'label' => 'Pouzdro',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $connectorGen->getCasePart()
                    ))
                    ->add('ContactCnt', 'integer', array(
                        'required' => true,
                        'label' => 'Počet kontaktů',
                        'error_bubbling' => true,
                        'attr' => array('min'=>0),
                        'data' => $connectorGen->getContactCnt()
                    ))
                    ->add('CurrentContact', 'number', array(
                        'required' => true,
                        'label' => 'Proud na kontakt [A]',
                        'error_bubbling' => true,
                        'data' => $connectorGen->getCurrentContact()
                    ))
                    ->add('MatingFactor', 'integer', array(
                        'required' => true,
                        'label' => 'Počet spoj/rozp za 1000h',
                        'error_bubbling' => true,
                        'data' => $connectorGen->getMatingFactor()
                    ))
                    ->add('PassiveTemp', 'integer', array(
                        'required' => true,
                        'label' => 'Pasivní oteplení [°C]',
                        'error_bubbling' => true,
                        'data' => $connectorGen->getPassiveTemp()
                    ))
                    ->getForm();
                return array('type'=> $type, 'formConGen' => $formConGen->createView(),
                    'IDPart' => $connectorGen->getIDPart(), 'Label' => $connectorGen->getLabel(), 'Lam' => $connectorGen->getLam());

            case 'spínač':
                //$RU = $em->getRepository('BakalarkaIkarosBundle:Switches');
                //$switch = $RU->findOneBy(array('ID_Part' => $id));

                $serviceSwitch = $this->get('ikaros_switchService');
                $switch = $serviceSwitch->getItem($id);
                $swType = $serviceSwitch->getSwitchTypeAll();

                foreach($swType as $m) {
                    $swTypeChoices[$m['Description']] = $m['Description'];
                }

                $formSwitch = $this->createFormBuilder($switch)
                    ->add('Environment', 'choice', array(
                        'label' => 'Prostředí',
                        'choices' => $envChoices,
                        'required' => true,
                        'data' => $switch->getEnvironment()
                    ))
                    ->add('Label', 'text', array(
                        'required' => true,
                        'label' => 'Název',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $switch->getLabel()
                    ))
                    ->add('Type', 'text', array(
                        'required' => false,
                        'label' => 'Typ',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $switch->getType()
                    ))
                    ->add('CasePart', 'text', array(
                        'required' => false,
                        'label' => 'Pouzdro',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $switch->getCasePart()
                    ))
                    ->add('SwitchType', 'choice', array(
                        'required' => true,
                        'label' => 'Popis',
                        'choices' => $swTypeChoices,
                        'data' => $switch->getSwitchType()
                    ))
                    ->add('Quality', 'choice', array(
                        'required' => true,
                        'label' => 'Kvalita',
                        'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower"),
                        'data' => $switch->getQuality()
                    ))
                    ->add('LoadType', 'choice', array(
                        'required' => true,
                        'label' => 'Typ zátěže',
                        'error_bubbling' => true,
                        'choices' => array("Resistive" => "Resistive", "Inductive" => "Inductive", "Lamp" => "Lamp"),
                        'data' => $switch->getLoadType()
                    ))
                    ->add('ContactCnt', 'integer', array(
                        'required' => true,
                        'label' => 'Počet kontaktů',
                        'error_bubbling' => true,
                        'attr' => array('min'=>0),
                        'data' => $switch->getContactCnt()
                    ))
                    ->add('OperatingCurrent', 'number', array(
                        'required' => true,
                        'label' => 'Pracovní proud [A]',
                        'error_bubbling' => true,
                        'data' => $switch->getOperatingCurrent()
                    ))
                    ->add('RatedResistiveCurrent', 'integer', array(
                        'required' => true,
                        'label' => 'Maximální proud [A]',
                        'error_bubbling' => true,
                        'data' => $switch->getRatedResistiveCurrent()
                    ))

                    ->getForm();

                return array('type'=> $type, 'formSwitch' => $formSwitch->createView(),
                    'IDPart' => $switch->getIDPart(), 'Label' => $switch->getLabel(), 'Lam' => $switch->getLam());

            case 'filtr':
                //$RU = $em->getRepository('BakalarkaIkarosBundle:Filter');
                //$filter = $RU->findOneBy(array('ID_Part' => $id));

                $serviceFilter = $this->get('ikaros_filterService');
                $filter = $serviceFilter->getItem($id);
                $filterType = $serviceFilter->getFilterTypeAll();

                foreach($filterType as $m) {
                    $filterTypeChoices[$m['Description']] = $m['Description'];
                }

                $formFilter = $this->createFormBuilder($filter)
                    ->add('Environment', 'choice', array(
                        'label' => 'Prostředí',
                        'choices' => $envChoices,
                        'required' => true,
                        'data' => $filter->getEnvironment()
                    ))
                    ->add('FilterType', 'choice', array(
                        'required' => true,
                        'label' => 'Popis',
                        'choices' => $filterTypeChoices,
                        'data' => $filter->getFilterType()
                    ))
                    ->add('Quality', 'choice', array(
                        'required' => true,
                        'label' => 'Kvalita',
                        'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower"),
                        'data' => $filter->getQuality()
                    ))
                    ->add('Label', 'text', array(
                        'required' => true,
                        'label' => 'Název',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $filter->getLabel()
                    ))
                    ->add('Type', 'text', array(
                        'required' => false,
                        'label' => 'Typ',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $filter->getType()
                    ))
                    ->add('CasePart', 'text', array(
                        'required' => false,
                        'label' => 'Pouzdro',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $filter->getCasePart()
                    ))
                    ->getForm();

                return array('type'=> $type, 'formFilter' => $formFilter->createView(),
                    'IDPart' => $filter->getIDPart(), 'Label' => $filter->getLabel(), 'Lam' => $filter->getLam());

            case 'měřič motohodin':
                $RU = $em->getRepository('BakalarkaIkarosBundle:RotDevElaps');
                $rotElaps = $RU->findOneBy(array('ID_Part' => $id));

                $formRotElaps = $this->createFormBuilder($rotElaps)
                    ->add('Environment', 'choice', array(
                        'label' => 'Prostředí',
                        'choices' => $envChoices,
                        'required' => true,
                        'data' => $rotElaps->getEnvironment()
                    ))
                    ->add('DevType', 'choice', array(
                        'required' => true,
                        'label' => 'Popis',
                        'choices' => array("A.C." => "A.C.", "Inverter Driven" => "Inverter Driven", "Commutator D.C." => "Commutator D.C."),
                        'data' => $rotElaps->getDevType()
                    ))
                    ->add('Label', 'text', array(
                        'required' => true,
                        'label' => 'Název',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $rotElaps->getLabel()
                    ))
                    ->add('Type', 'text', array(
                        'required' => false,
                        'label' => 'Typ',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $rotElaps->getType()
                    ))
                    ->add('CasePart', 'text', array(
                        'required' => false,
                        'label' => 'Pouzdro',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $rotElaps->getCasePart()
                    ))
                    ->add('TempOperational', 'integer', array(
                        'required' => true,
                        'label' => 'Provozní teplota [°C]',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $rotElaps->getTempOperational()
                    ))
                    ->add('TempMax', 'integer', array(
                        'required' => true,
                        'label' => 'Maximální teplota [°C]',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $rotElaps->getTempMax()
                    ))
                    ->getForm();

                return array('type'=> $type, 'formRotElaps' => $formRotElaps->createView(),
                    'IDPart' => $rotElaps->getIDPart(), 'Label' => $rotElaps->getLabel(), 'Lam' => $rotElaps->getLam());

            case 'permaktron':
                $RU = $em->getRepository('BakalarkaIkarosBundle:TubeWave');
                $tubeWave = $RU->findOneBy(array('ID_Part' => $id));

                $formTubeWave= $this->createFormBuilder($tubeWave)
                    ->add('Environment', 'choice', array(
                        'label' => 'Prostředí',
                        'choices' => $envChoices,
                        'required' => true,
                        'data' => $tubeWave->getEnvironment()
                    ))
                    ->add('Label', 'text', array(
                        'required' => true,
                        'label' => 'Název',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $tubeWave->getLabel()
                    ))
                    ->add('Type', 'text', array(
                        'required' => false,
                        'label' => 'Typ',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $tubeWave->getType()
                    ))
                    ->add('CasePart', 'text', array(
                        'required' => false,
                        'label' => 'Pouzdro',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'data' => $tubeWave->getCasePart()
                    ))
                    ->add('Power', 'integer', array(
                        'required' => true,
                        'label' => 'Výkon (10-40000) [W]',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'attr' => array('min'=>10, 'max'=>40000),
                        'data' => $tubeWave->getPower()
                    ))
                    ->add('Frequency', 'number', array(
                        'required' => true,
                        'label' => 'Frekvence (0.1-18) [GHz]',
                        'error_bubbling' => true,
                        'max_length' => 64,
                        'attr' => array('min'=>0.1, 'max'=>18),
                        'data' => $tubeWave->getFrequency()
                    ))
                    ->getForm();

                return array('type'=> $type, 'formTubeWave' => $formTubeWave->createView(),
                    'IDPart' => $tubeWave->getIDPart(), 'Label' => $tubeWave->getLabel(), 'Lam' => $tubeWave->getLam());


        }


        return array('type' => $type);
    }


//====================================================================================================================
    /**
     * @Route("/delPartID/{id}", name="delPartID")
     * @Template()
     */
    public function delPartIDAction($id)   {
        $service = $this->get('ikaros_partService');
        $msg = $service->subtractLam($id);

        if(strpos($msg, "lam_") != 0)
            return $this->redirect($this->generateUrl('mySystems'));

        return $this->redirect($this->generateUrl('mySystems'));
    }
//====================================================================================================================
    /**
     * @Route("/delPart", name="delPart")
     * @Template()
     */
    public function delPartAction()    {
        $post = $this->get('request')->request;
        $id = $post->get('id');

        $service = $this->get('ikaros_partService');
        $msg = $service->subtractLam($id);

        if(strpos($msg, "lam_") != 0)
            return new Response(
            json_encode(array(
                'e' => $msg
            )),
            400,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );

        $lam = substr($msg, 4);

        return new Response(
            json_encode(array(
                'lam' => floatval($lam)
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );

    }


//====================================================================================================================
    /**
     * @Route("/editPart/{id}", name="editPart")
     * @Template()
     */
    public function editPartAction($id) {
        $post = $this->get('request')->request;
        $mode = $post->get('mode');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->form;

        $em =  $this->getDoctrine()->getManager();
        $servicePart = $this->get('ikaros_partService');
        $servicePCB = $this->get('ikaros_pcbService');
        $serviceSystem = $this->get('ikaros_systemService');

        $part = $servicePart->getItem($id);
        $pcb = $servicePCB->getItem($part->getPCBID());
        $system = $serviceSystem->getItem($pcb->getSystemID());

        switch ($mode) {
            case 'resistor':
                /*$RU = $em->getRepository('BakalarkaIkarosBundle:Resistor');
                $res = $RU->findOneBy(array('ID_Part' => $id));
                $res->setParams($obj);

                $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
                $pcb = $RU->findOneBy(array('ID_PCB' => $res->getPCBID()));

                $RU = $em->getRepository('BakalarkaIkarosBundle:System');
                $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));*/

                $serviceResistor = $this->get('ikaros_resistorService');
                $res = $serviceResistor->getItem($id);
                $res->setParams($obj);

                //$pcb = $servicePCB->getItem($res->getPCBID());
                //$system = $serviceSystem->getItem($pcb->getSystemID());

                $sysTemp = $system->getTemp();
                $res->setTemp($sysTemp + $res->getDPTemp() + $res->getPassiveTemp());

                $oldLam = $res->getLam();
                //$lambda = $this->lamRes($res);

                $lambda = $serviceResistor->lamResistor($res);
                $e = $servicePart->setLams($lambda, $res, -1, $oldLam);

                /*$res->setLam($lambda);
                $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda - $oldLam);
                $system->setLam($system->getLam() + $lambda - $oldLam);

                try {
                    $em->persist($res);
                    $em->persist($pcb);
                    $em->persist($system);

                } catch (\Exception $e) {*/

                if($e != "") {
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
                else
                    return new Response(
                        json_encode(array(
                            'Lam' => $res->getLam(),
                            'Label' => $res->getLabel()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
                break;

            case 'fuse':
                //$RU = $em->getRepository('BakalarkaIkarosBundle:Fuse');
                //$fuse = $RU->findOneBy(array('ID_Part' => $id));

                $serviceFuse = $this->get('ikaros_fuseService');
                $fuse = $serviceFuse->getItem($id);
                $fuse->setParams($obj);

                /*$em =  $this->getDoctrine()->getManager();
                $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
                $pcb = $RU->findOneBy(array('ID_PCB' => $fuse->getPCBID()));

                $RU = $em->getRepository('BakalarkaIkarosBundle:System');
                $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));*/

                $oldLam = $fuse->getLam();
                $lambda = $serviceFuse->lamFuse($fuse);


                $e = $servicePart->setLams($lambda, $fuse, -1, $oldLam);

                /*$fuse->setLam($lambda);
                $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda - $oldLam);
                $system->setLam($system->getLam() + $lambda - $oldLam);

                try {
                    $em->persist($fuse);
                    $em->persist($pcb);
                    $em->persist($system);
                    $em->flush();

                } catch (\Exception $e) {*/
                if($e != "")
                    return new Response(
                        json_encode(array(
                            'e' => $e
                        )),
                        400,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
                //}
                else
                    return new Response(
                        json_encode(array(
                            'Lam' => $fuse->getLam(),
                            'Label' => $fuse->getLabel()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
                break;
            case 'capacitor':
                /*$RU = $em->getRepository('BakalarkaIkarosBundle:Capacitor');
                $cap = $RU->findOneBy(array('ID_Part' => $id));
                $cap->setParams($obj);*/

                /*$em =  $this->getDoctrine()->getManager();
                $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
                $pcb = $RU->findOneBy(array('ID_PCB' => $cap->getPCBID()));

                $RU = $em->getRepository('BakalarkaIkarosBundle:System');
                $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));*/

                $serviceCapacitor = $this->get('ikaros_capacitorService');
                $cap = $serviceCapacitor->getItem($id);
                $cap->setParams($obj);

                //$pcb = $servicePCB->getItem($cap->getPCBID());
                //$system = $serviceSystem->getItem($pcb->getSystemID());

                $sysTemp = $system->getTemp();
                $cap->setTemp($sysTemp + $cap->getPassiveTemp());

                $oldLam = $cap->getLam();
                //$lambda = $this->lamCap($cap);

                $lambda = $serviceCapacitor->lamCapacitor($cap);

                $e = $servicePart->setLams($lambda, $cap, -1, $oldLam);

                /*$cap->setLam($lambda);
                $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda - $oldLam);
                $system->setLam($system->getLam() + $lambda - $oldLam);


                try {
                    $em->persist($cap);
                    $em->persist($pcb);
                    $em->persist($system);
                    $em->flush();

                } catch (\Exception $e) {*/
                if($e != "") {
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
                else
                    return new Response(
                        json_encode(array(
                            'Lam' => $cap->getLam(),
                            'Label' => $cap->getLabel()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
                break;

            case 'connection':
                /*$RU = $em->getRepository('BakalarkaIkarosBundle:Connections');
                $con = $RU->findOneBy(array('ID_Part' => $id));
                $con->setParams($obj);

                $em =  $this->getDoctrine()->getManager();
                $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
                $pcb = $RU->findOneBy(array('ID_PCB' => $con->getPCBID()));

                $RU = $em->getRepository('BakalarkaIkarosBundle:System');
                $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));*/

                $serviceConnection = $this->get('ikaros_connectionService');
                $con = $serviceConnection->getItem($id);
                $con->setParams($obj);

                //$pcb = $servicePCB->getItem($con->getPCBID());
                //$system = $serviceSystem->getItem($pcb->getSystemID());

                $oldLam = $con->getLam();
                //$lambda = $this->lamConnection($con);
                $service = $this->get('ikaros_connectionService');
                $lambda = $service->lamConnection($con);

                $e = $servicePart->setLams($lambda, $con, -1, $oldLam);

                /*$con->setLam($lambda);
                $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda - $oldLam);
                $system->setLam($system->getLam() + $lambda - $oldLam);

                try {
                    $em->persist($con);
                    $em->persist($pcb);
                    $em->persist($system);
                    $em->flush();

                } catch (\Exception $e) {*/
                if(e != "") {
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
                else
                    return new Response(
                    json_encode(array(
                        'Label' => $con->getLabel(),
                        'Lam' => $con->getLam()
                    )),
                    200,
                    array(
                        'Content-Type' => 'application/json; charset=utf-8'
                    )
                    );
                break;

            case 'conSoc':
                /*$RU = $em->getRepository('BakalarkaIkarosBundle:ConnectorSoc');
                $conSoc = $RU->findOneBy(array('ID_Part' => $id));

                $conSoc->setParams($obj);

                $em =  $this->getDoctrine()->getManager();
                $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
                $pcb = $RU->findOneBy(array('ID_PCB' => $conSoc->getPCBID()));

                $RU = $em->getRepository('BakalarkaIkarosBundle:System');
                $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));*/

                $serviceConSoc = $this->get('ikaros_connectorService');
                $conSoc = $serviceConSoc->getItemSoc($id);
                $conSoc->setParams($obj);

                $oldLam = $conSoc->getLam();
                //$lambda = $this->lamConSoc($conSoc);
                $service = $this->get('ikaros_connectorService');
                $lambda = $service->lamConSoc($conSoc);

                /*$conSoc->setLam($lambda);
                $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda - $oldLam);
                $system->setLam($system->getLam() + $lambda - $oldLam);

                try {
                    $em->persist($conSoc);
                    $em->persist($pcb);
                    $em->persist($system);
                    $em->flush();

                } catch (\Exception $e) {*/

                $e = $servicePart->setLams($lambda, $conSoc, -1, $oldLam);

                if($e != "") {
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
                else
                    return new Response(
                        json_encode(array(
                            'Label' => $conSoc->getLabel(),
                            'Lam' => $conSoc->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
                break;

            case 'conGen':
                /*$RU = $em->getRepository('BakalarkaIkarosBundle:ConnectorGen');
                $conGen = $RU->findOneBy(array('ID_Part' => $id));

                $conGen->setParams($obj);

                $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
                $pcb = $RU->findOneBy(array('ID_PCB' => $conGen->getPCBID()));

                $RU = $em->getRepository('BakalarkaIkarosBundle:System');
                $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));*/

                $serviceConGen = $this->get('ikaros_connectorService');
                $conGen = $serviceConGen->getItemGen($id);
                $conGen->setParams($obj);
                $conGen->setTemp($system->getTemp() + $conGen->getPassiveTemp());

                $oldLam = $conGen->getLam();
                //$lambda = $this->lamConGen($conGen);
                $service = $this->get('ikaros_connectorService');
                $lambda = $service->lamConGen($conGen);

                /*$conGen->setLam($lambda);
                $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda - $oldLam);
                $system->setLam($system->getLam() + $lambda - $oldLam);

                try {
                    $em->persist($conGen);
                    $em->persist($pcb);
                    $em->persist($system);
                    $em->flush();

                } catch (\Exception $e) {*/
                $e = $servicePart->setLams($lambda, $conGen, -1, $oldLam);

                if($e != "") {
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
                else
                    return new Response(
                        json_encode(array(
                            'Label' => $conGen->getLabel(),
                            'Lam' => $conGen->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
                break;

            case 'switch':
                /*$RU = $em->getRepository('BakalarkaIkarosBundle:Switches');
                $switch = $RU->findOneBy(array('ID_Part' => $id));

                $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
                $pcb = $RU->findOneBy(array('ID_PCB' => $switch->getPCBID()));

                $RU = $em->getRepository('BakalarkaIkarosBundle:System');
                $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));

                $switch->setParams($obj);*/

                $serviceSwitch = $this->get('ikaros_switchService');
                $switch = $serviceSwitch->getItem($id);
                $switch->setParams($obj);

                $oldLam = $switch->getLam();
                //$lambda = $this->lamSwitch($switch);
                $service = $this->get('ikaros_switchService');
                $lambda = $service->lamSwitch($switch);

                /*$switch->setLam($lambda);
                $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda - $oldLam);
                $system->setLam($system->getLam() + $lambda - $oldLam);

                try {
                    $em->persist($switch);
                    $em->persist($pcb);
                    $em->persist($system);
                    $em->flush();

                } catch (\Exception $e) {*/
                $e = $servicePart->setLams($lambda, $switch, -1, $oldLam);

                if($e != "") {
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
                else
                    return new Response(
                        json_encode(array(
                            'Label' => $switch->getLabel(),
                            'Lam' => $switch->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
                break;

            case 'filter':
                /*$RU = $em->getRepository('BakalarkaIkarosBundle:Filter');
                $filter = $RU->findOneBy(array('ID_Part' => $id));

                $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
                $pcb = $RU->findOneBy(array('ID_PCB' => $filter->getPCBID()));

                $RU = $em->getRepository('BakalarkaIkarosBundle:System');
                $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));

                $filter->setParams($obj);*/

                $serviceFilter = $this->get('ikaros_filterService');
                $filter = $serviceFilter->getItem($id);
                $filter->setParams($obj);

                $oldLam = $filter->getLam();
                //$lambda = $this->lamFilter($filter);
                $service = $this->get('ikaros_filterService');
                $lambda = $service->lamFilter($filter);

                /*$filter->setLam($lambda);
                $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda - $oldLam);
                $system->setLam($system->getLam() + $lambda - $oldLam);

                try {
                    $em->persist($filter);
                    $em->persist($pcb);
                    $em->persist($system);
                    $em->flush();

                } catch (\Exception $e) {*/
                $e = $servicePart->setLams($lambda, $filter, -1, $oldLam);

                if($e != "") {
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
                else
                    return new Response(
                        json_encode(array(
                            'Label' => $filter->getLabel(),
                            'Lam' => $filter->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );

            case 'rotElaps':
                /*$RU = $em->getRepository('BakalarkaIkarosBundle:RotDevElaps');
                $rotElaps = $RU->findOneBy(array('ID_Part' => $id));

                $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
                $pcb = $RU->findOneBy(array('ID_PCB' => $rotElaps->getPCBID()));

                $RU = $em->getRepository('BakalarkaIkarosBundle:System');
                $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));

                $rotElaps->setParams($obj);*/

                $serviceRotDevElaps = $this->get('ikaros_rotDevElapsService');
                $rotElaps = $serviceRotDevElaps->getItem($id);
                $rotElaps->setParams($obj);

                $oldLam = $rotElaps->getLam();
                //$lambda = $this->lamRotElaps($rotElaps);
                $service = $this->get('ikaros_rotDevElapsService');
                $lambda = $service->lamRotElaps($rotElaps);

                /*$rotElaps->setLam($lambda);
                $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda - $oldLam);
                $system->setLam($system->getLam() + $lambda - $oldLam);

                try {
                    $em->persist($rotElaps);
                    $em->persist($pcb);
                    $em->persist($system);
                    $em->flush();

                } catch (\Exception $e) {*/
                $e = $servicePart->setLams($lambda, $rotElaps, -1, $oldLam);

                if($e != "") {
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
                else
                    return new Response(
                        json_encode(array(
                            'Label' => $rotElaps->getLabel(),
                            'Lam' => $rotElaps->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );

            case 'tubeWave':
                /*$RU = $em->getRepository('BakalarkaIkarosBundle:TubeWave');
                $tubeWave = $RU->findOneBy(array('ID_Part' => $id));

                $RU = $em->getRepository('BakalarkaIkarosBundle:PCB');
                $pcb = $RU->findOneBy(array('ID_PCB' => $tubeWave->getPCBID()));

                $RU = $em->getRepository('BakalarkaIkarosBundle:System');
                $system = $RU->findOneBy(array('ID_System' => $pcb->getSystemID()));

                $tubeWave->setParams($obj);*/

                $serviceTubeWave = $this->get('ikaros_TubeWaveService');
                $tubeWave = $serviceTubeWave->getItem($id);
                $tubeWave->setParams($obj);

                $oldLam = $tubeWave->getLam();
                //$lambda = $this->lamTubeWave($tubeWave);
                $service = $this->get('ikaros_tubeWaveService');
                $lambda = $service->lamTubeWave($tubeWave);

                /*$tubeWave->setLam($lambda);
                $pcb->setSumPartsLam($pcb->getSumPartsLam() + $lambda -$oldLam);
                $system->setLam($system->getLam() + $lambda -$oldLam);

                try {
                    $em->persist($tubeWave);
                    $em->persist($pcb);
                    $em->persist($system);
                    $em->flush();

                } catch (\Exception $e) {*/
                $e = $servicePart->setLams($lambda, $tubeWave, -1, $oldLam);

                if($e != "") {
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
                else
                    return new Response(
                        json_encode(array(
                            'Label' => $tubeWave->getLabel(),
                            'Lam' => $tubeWave->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
        }


        //KONEC SWITCHE
        //$em->flush();

        return new Response(
            json_encode(array(
                'e' => "neco se pokazilo"
            )),
            400,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }
}
