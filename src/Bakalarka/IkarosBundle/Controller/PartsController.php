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

        $serviceResistor = $this->get('ikaros_resistorService');
        $QualityChoices = $serviceResistor->getResQualityAll();
        $MatChoices = $serviceResistor->getResMaterialAll();

        /*foreach($qr as $q) {
            $QualityChoices[$q['Value']] = $q['Description'];
        }

        foreach($mat as $m) {
            $MatChoices[$m['ResShortcut']] = $m['ResShortcut'];
        }*/

        $formResistor = $this->createForm(new ResistorForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'qualityChoices' => $QualityChoices , 'matChoices' => $MatChoices));


  //---Capacitor form---------------------------------------------------------------------------

        $serviceCapacitor = $this->get('ikaros_capacitorService');
        $QualityChoicesC = $serviceCapacitor->getCapQualityAll();
        $MatChoicesC = $serviceCapacitor->getCapMaterialAll();

        /*foreach($qc as $q) {
            $QualityChoicesC[$q['Value']] = $q['Description'];
        }

        foreach($mc as $m) {
            $MatChoicesC[$m['CapShortcut']] = $m['CapShortcut'];
        }*/

        $formCapacitor = $this->createForm(new CapacitorForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'qualityChoicesC' => $QualityChoicesC , 'matChoicesC' => $MatChoicesC));


//---Fuse form---------------------------------------------------------------------------

        $service = $this->get('ikaros_systemService');
        $envChoices = $service->getEnvChoices();

        $formFuse = $this->createForm(new FuseForm(), array(), array('envChoices' => $envChoices , 'sysEnv' => $sysEnv));

//---Connection form---------------------------------------------------------------------------

        $serviceConnection = $this->get('ikaros_connectionService');
        $conTypeChoices = $serviceConnection->getConTypeAll();

        /*foreach($conType as $m) {
            $conTypeChoices[$m['Lamb']] = $m['Description'];
        }*/

        $formConnection = $this->createForm(new ConnectionForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'conTypeChoices' => $conTypeChoices));

//---Connector - socket form---------------------------------------------------------------------------

        $serviceConnector = $this->get('ikaros_connectorService');
        $conSocTypeChoices = $serviceConnector->getConSocTypeAll();

        /*foreach($conSocType as $m) {
            $conSocTypeChoices[$m['Description']] = $m['Description'];
        }*/

        $formConSoc = $this->createForm(new ConnectorSocForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'conSocTypeChoices' => $conSocTypeChoices));

//---Connector - general form---------------------------------------------------------------------------

        $conGenTypeChoices = $serviceConnector->getConGenTypeAll();

        /*foreach($conGenType as $m) {
            $conGenTypeChoices[$m['Description']] = $m['Description'];
        }*/


        $formConGen = $this->createForm(new ConnectorGenForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'conGenTypeChoices' => $conGenTypeChoices));

//---Switch form---------------------------------------------------------------------------

        $serviceSwitch = $this->get('ikaros_switchService');
        $swTypeChoices = $serviceSwitch->getSwitchTypeAll();

        /*foreach($swType as $m) {
            $swTypeChoices[$m['Description']] = $m['Description'];
        }*/

        $formSwitch = $this->createForm(new SwitchForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'swTypeChoices' => $swTypeChoices));

//---Filter form---------------------------------------------------------------------------

        $serviceFilter = $this->get('ikaros_filterService');
        $filterTypeChoices = $serviceFilter->getFilterTypeAll();

        /*foreach($filterType as $m) {
            $filterTypeChoices[$m['Description']] = $m['Description'];
        }*/

        $formFilter = $this->createForm(new FilterForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'filterTypeChoices' => $filterTypeChoices));

//---Rot dev elaps form---------------------------------------------------------------------------

        $formRotElaps = $this->createForm(new RotDevElapsForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv));

//---TubeWave form---------------------------------------------------------------------------

        $formTubeWave = $this->createForm(new TubeWaveForm(), array(),array('envChoices' => $envChoices , 'sysEnv' => $sysEnv));


        $resistorMatDescAll = $serviceResistor->getResMaterialDescAll();
        $capacitorMatDescAll = $serviceCapacitor->getCapMaterialDescAll();
        return $this->render('BakalarkaIkarosBundle:Parts:newPart.html.twig', array(
            'system' => $system,  'pcb' => $pcb, 'e' => "",
            'formResistor' => $formResistor->createView(), 'resistors' => $resistors, 'matRes' => $resistorMatDescAll,
            'formCapacitor' => $formCapacitor->createView(), 'capacitors' => $capacitors, 'matCap' => $capacitorMatDescAll,
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
                $QualityChoices = $serviceRes->getResQualityAll();
                $MatChoices = $serviceRes->getResMaterialAll();

                /*foreach($qr as $q) {
                    $QualityChoices[$q['Value']] = $q['Description'];
                }

                foreach($mat as $m) {
                    $MatChoices[$m['ResShortcut']] = $m['ResShortcut'];
                }*/

                //$resistor = new Resistor();
                /*$formResistor = $this->createFormBuilder($res)
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

                    ->getForm();*/

                $resistorArray = $res->to_array();
                $formResistor = $this->createForm(new ResistorForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'qualityChoices' => $QualityChoices ,
                        'matChoices' => $MatChoices, 'resistor' => $resistorArray));

                return array('type'=> $type, 'formResistor' => $formResistor->createView(),'IDPart' => $res->getIDPart(), 'Lam' => $res->getLam(), 'Label' => $res->getLabel());

            case 'pojistka':
                //$RU = $em->getRepository('BakalarkaIkarosBundle:Fuse');
                //$fuse = $RU->findOneBy(array('ID_Part' => $id));

                $serviceFuse = $this->get('ikaros_fuseService');
                $fuse = $serviceFuse->getItem($id);

                $fuseArray = $fuse->to_array();
                $formFuse = $this->createForm(new FuseForm(), array(), array('envChoices' => $envChoices , 'sysEnv' => "", 'fuse' => $fuseArray));


                /*$formFuse = $this->createFormBuilder($fuse)
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
                    ->getForm();*/

                return array('type'=> $type, 'formFuse' => $formFuse->createView(),'IDPart' => $fuse->getIDPart(), 'Label' => $fuse->getLabel(), 'Lam' => $fuse->getLam());

            case 'kondenzátor':
                //$RU = $em->getRepository('BakalarkaIkarosBundle:Capacitor');
                //$capacitor = $RU->findOneBy(array('ID_Part' => $id));

                $serviceCap = $this->get('ikaros_capacitorService');

                $capacitor = $serviceCap->getItem($id);
                $QualityChoicesC = $serviceCap->getCapQualityAll();
                $MatChoicesC = $serviceCap->getCapMaterialAll();

                /*foreach($qc as $q) {
                    $QualityChoicesC[$q['Value']] = $q['Description'];
                }

                foreach($mc as $m) {
                    $MatChoicesC[$m['CapShortcut']] = $m['CapShortcut'];
                }*/

                $capacitorArray = $capacitor->to_array();
                $formCapacitor = $this->createForm(new CapacitorForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'qualityChoicesC' => $QualityChoicesC ,
                        'matChoicesC' => $MatChoicesC, 'capacitor' => $capacitorArray));


                /*$formCapacitor = $this->createFormBuilder($capacitor)
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

                    ->getForm();*/


                return array('type'=> $type, 'formCapacitor' => $formCapacitor->createView(),'IDPart' => $capacitor->getIDPart(), 'Label' => $capacitor->getLabel(), 'Lam' => $capacitor->getLam());

            case 'propojení':
                $serviceConnection = $this->get('ikaros_connectionService');
                $con = $serviceConnection->getItem($id);
                $conTypeChoices = $serviceConnection->getConTypeAll();

                //$RU = $em->getRepository('BakalarkaIkarosBundle:Connections');
                //$con = $RU->findOneBy(array('ID_Part' => $id));

                /*foreach($conType as $m) {
                    $conTypeChoices[$m['Lamb']] = $m['Description'];
                }*/

                $conArray = $con->to_array();

                /*$formConnection = $this->createFormBuilder($con)
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
                    ->getForm();*/

                $formConnection = $this->createForm(new ConnectionForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'conTypeChoices' => $conTypeChoices,
                        'connection' => $conArray));

                return array('type'=> $type, 'formConnection' => $formConnection->createView(),'IDPart' => $con->getIDPart(), 'Label' => $con->getLabel(), 'Lam' => $con->getLam());

            case 'konektor, soket':
                $serviceConSoc = $this->get('ikaros_connectorService');

                $connectorSoc = $serviceConSoc->getItemSoc($id);
                $conSocTypeChoices = $serviceConSoc->getConSocTypeAll();

                /*foreach($conSocType as $m) {
                    $conSocTypeChoices[$m['Description']] = $m['Description'];
                }*/

                $connectorSocArray = $connectorSoc->to_array();

                /*$formConSoc = $this->createFormBuilder($connectorSoc)
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
                    ->getForm();*/

                $formConSoc = $this->createForm(new ConnectorSocForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'conSocTypeChoices' => $conSocTypeChoices, 'connectorSoc' => $connectorSocArray));

                return array('type'=> $type, 'formConSoc' => $formConSoc->createView(),
                    'IDPart' => $connectorSoc->getIDPart(), 'Label' => $connectorSoc->getLabel(), 'Lam' => $connectorSoc->getLam());

            case 'konektor, obecný':
                $serviceConGen = $this->get('ikaros_connectorService');

                $connectorGen = $serviceConGen->getItemGen($id);
                $conGenTypeChoices = $serviceConGen->getConGenTypeAll();

                /*foreach($conGenType as $m) {
                    $conGenTypeChoices[$m['Description']] = $m['Description'];
                }*/

                //$RU = $em->getRepository('BakalarkaIkarosBundle:ConnectorGen');
                //$connectorGen = $RU->findOneBy(array('ID_Part' => $id));

                $connectorGenArray = $connectorGen->to_array();

                /*$formConGen = $this->createFormBuilder($connectorGen)
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
                    ->getForm();*/

                $formConGen = $this->createForm(new ConnectorGenForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'conGenTypeChoices' => $conGenTypeChoices, 'connectorGen' => $connectorGenArray));

                return array('type'=> $type, 'formConGen' => $formConGen->createView(),
                    'IDPart' => $connectorGen->getIDPart(), 'Label' => $connectorGen->getLabel(), 'Lam' => $connectorGen->getLam());

            case 'spínač':
                //$RU = $em->getRepository('BakalarkaIkarosBundle:Switches');
                //$switch = $RU->findOneBy(array('ID_Part' => $id));

                $serviceSwitch = $this->get('ikaros_switchService');
                $switch = $serviceSwitch->getItem($id);
                $swTypeChoices = $serviceSwitch->getSwitchTypeAll();

                /*foreach($swType as $m) {
                    $swTypeChoices[$m['Description']] = $m['Description'];
                }*/

                $switchArray = $switch->to_array();

                /*$formSwitch = $this->createFormBuilder($switch)
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

                    ->getForm();*/

                $formSwitch = $this->createForm(new SwitchForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'swTypeChoices' => $swTypeChoices, 'switch' => $switchArray));

                return array('type'=> $type, 'formSwitch' => $formSwitch->createView(),
                    'IDPart' => $switch->getIDPart(), 'Label' => $switch->getLabel(), 'Lam' => $switch->getLam());

            case 'filtr':
                //$RU = $em->getRepository('BakalarkaIkarosBundle:Filter');
                //$filter = $RU->findOneBy(array('ID_Part' => $id));

                $serviceFilter = $this->get('ikaros_filterService');
                $filter = $serviceFilter->getItem($id);
                $filterTypeChoices = $serviceFilter->getFilterTypeAll();

                /*foreach($filterType as $m) {
                    $filterTypeChoices[$m['Description']] = $m['Description'];
                }*/

                $filterArray = $filter->to_array();

                /*$formFilter = $this->createFormBuilder($filter)
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
                    ->getForm();*/

                $formFilter = $this->createForm(new FilterForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'filterTypeChoices' => $filterTypeChoices, 'filter' => $filterArray));

                return array('type'=> $type, 'formFilter' => $formFilter->createView(),
                    'IDPart' => $filter->getIDPart(), 'Label' => $filter->getLabel(), 'Lam' => $filter->getLam());

            case 'měřič motohodin':
                //$RU = $em->getRepository('BakalarkaIkarosBundle:RotDevElaps');
                //$rotElaps = $RU->findOneBy(array('ID_Part' => $id));

                $serviceRotElaps = $this->get('ikaros_rotDevElapsService');
                $rotElaps = $serviceRotElaps->getItem($id);
                $rotElapsArray = $rotElaps->to_array();

                /*$formRotElaps = $this->createFormBuilder($rotElaps)
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
                    ->getForm();*/

                $formRotElaps = $this->createForm(new RotDevElapsForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'rotElaps' => $rotElapsArray));

                return array('type'=> $type, 'formRotElaps' => $formRotElaps->createView(),
                    'IDPart' => $rotElaps->getIDPart(), 'Label' => $rotElaps->getLabel(), 'Lam' => $rotElaps->getLam());

            case 'permaktron':
                /*$RU = $em->getRepository('BakalarkaIkarosBundle:TubeWave');
                $tubeWave = $RU->findOneBy(array('ID_Part' => $id));*/

                $serviceTubeWave = $this->get('ikaros_tubeWaveService');
                $tubeWave = $serviceTubeWave->getItem($id);
                $tubeWaveArray = $tubeWave->to_array();

                /*$formTubeWave= $this->createFormBuilder($tubeWave)
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
                    ->getForm();*/

                $formTubeWave = $this->createForm(new TubeWaveForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'tubeWave' => $tubeWaveArray));

                return array('type'=> $type, 'formTubeWave' => $formTubeWave->createView(),
                    'IDPart' => $tubeWave->getIDPart(), 'Label' => $tubeWave->getLabel(), 'Lam' => $tubeWave->getLam());
        }
        return array('type' => $type);
    }


//====================================================================================================================
    /**
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
        //$obj = $objF->form;

        $servicePart = $this->get('ikaros_partService');
        $servicePCB = $this->get('ikaros_pcbService');
        $serviceSystem = $this->get('ikaros_systemService');

        $part = $servicePart->getItem($id);
        $pcb = $servicePCB->getItem($part->getPCBID());
        $system = $serviceSystem->getItem($pcb->getSystemID());

        switch ($mode) {
            case 'resistor':
                $obj = $objF->resistorForm;
                $serviceResistor = $this->get('ikaros_resistorService');
                $res = $serviceResistor->getItem($id);
                $res->setParams($obj);

                $sysTemp = $system->getTemp();
                $res->setTemp($sysTemp + $res->getDPTemp() + $res->getPassiveTemp());

                $oldLam = $res->getLam();

                $lambda = $serviceResistor->lamResistor($res);
                $e = $servicePart->setLams($lambda, $res, -1, $oldLam);

                if($e == "")
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
                $obj = $objF->fuseForm;
                $serviceFuse = $this->get('ikaros_fuseService');
                $fuse = $serviceFuse->getItem($id);
                $fuse->setParams($obj);

                $oldLam = $fuse->getLam();
                $lambda = $serviceFuse->lamFuse($fuse);

                $e = $servicePart->setLams($lambda, $fuse, -1, $oldLam);

                if($e == "")
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
                $obj = $objF->capacitorForm;
                $serviceCapacitor = $this->get('ikaros_capacitorService');
                $cap = $serviceCapacitor->getItem($id);
                $cap->setParams($obj);

                $sysTemp = $system->getTemp();
                $cap->setTemp($sysTemp + $cap->getPassiveTemp());

                $oldLam = $cap->getLam();
                $lambda = $serviceCapacitor->lamCapacitor($cap);

                $e = $servicePart->setLams($lambda, $cap, -1, $oldLam);

                if($e == "")
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
                $obj = $objF->connectionForm;
                $serviceConnection = $this->get('ikaros_connectionService');
                $con = $serviceConnection->getItem($id);
                $con->setParams($obj);

                $oldLam = $con->getLam();
                $lambda = $serviceConnection->lamConnection($con);

                $e = $servicePart->setLams($lambda, $con, -1, $oldLam);

                if($e == "")
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
                $obj = $objF->connectorSocForm;
                $serviceConSoc = $this->get('ikaros_connectorService');
                $conSoc = $serviceConSoc->getItemSoc($id);
                $conSoc->setParams($obj);

                $oldLam = $conSoc->getLam();
                $lambda = $serviceConSoc->lamConSoc($conSoc);

                $e = $servicePart->setLams($lambda, $conSoc, -1, $oldLam);

                if($e == "")
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
                $obj = $objF->connectorGenForm;
                $serviceConGen = $this->get('ikaros_connectorService');
                $conGen = $serviceConGen->getItemGen($id);
                $conGen->setParams($obj);
                $conGen->setTemp($system->getTemp() + $conGen->getPassiveTemp());

                $oldLam = $conGen->getLam();
                $lambda = $serviceConGen->lamConGen($conGen);

                $e = $servicePart->setLams($lambda, $conGen, -1, $oldLam);

                if($e == "")
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
                $obj = $objF->switchForm;
                $serviceSwitch = $this->get('ikaros_switchService');
                $switch = $serviceSwitch->getItem($id);
                $switch->setParams($obj);

                $oldLam = $switch->getLam();
                $lambda = $serviceSwitch->lamSwitch($switch);

                $e = $servicePart->setLams($lambda, $switch, -1, $oldLam);

                if($e == "")
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
                $obj = $objF->filterForm;
                $serviceFilter = $this->get('ikaros_filterService');
                $filter = $serviceFilter->getItem($id);
                $filter->setParams($obj);

                $oldLam = $filter->getLam();
                $lambda = $serviceFilter->lamFilter($filter);

                $e = $servicePart->setLams($lambda, $filter, -1, $oldLam);

                if($e == "")
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
                break;

            case 'rotElaps':
                $obj = $objF->rotDevElapsForm;
                $serviceRotDevElaps = $this->get('ikaros_rotDevElapsService');
                $rotElaps = $serviceRotDevElaps->getItem($id);
                $rotElaps->setParams($obj);

                $oldLam = $rotElaps->getLam();
                $lambda = $serviceRotDevElaps->lamRotElaps($rotElaps);

                $e = $servicePart->setLams($lambda, $rotElaps, -1, $oldLam);

                if($e == "")
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
                $obj = $objF->tubeWaveForm;
                $serviceTubeWave = $this->get('ikaros_TubeWaveService');
                $tubeWave = $serviceTubeWave->getItem($id);
                $tubeWave->setParams($obj);

                $oldLam = $tubeWave->getLam();
                $lambda = $serviceTubeWave->lamTubeWave($tubeWave);

                $e = $servicePart->setLams($lambda, $tubeWave, -1, $oldLam);

                if($e == "")
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
}
