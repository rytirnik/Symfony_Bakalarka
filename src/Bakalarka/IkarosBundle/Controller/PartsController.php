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

use Bakalarka\IkarosBundle\Entity\Resistor;
use Bakalarka\IkarosBundle\Entity\Capacitor;
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

class PartsController extends Controller {

    /**
     * @Route("/newPart/{id}", name="newPart")
     * @Template()
     */
    public function newPartAction($id)    {
        $servicePcb = $this->get('ikaros_pcbService');
        $serviceSystem = $this->get('ikaros_systemService');

        $pcb = $servicePcb->getItem($id);
        $system = $serviceSystem->getItem($pcb->getSystemID());
        $envChoices = $serviceSystem->getEnvChoices();
        $sysEnv = $system->getEnvironment();

        $serviceResistor = $this->get('ikaros_resistorService');
        $serviceCapacitor = $this->get('ikaros_capacitorService');
        $serviceFuse  = $this->get('ikaros_fuseService');
        $serviceConnection = $this->get('ikaros_connectionService');
        $serviceConnector = $this->get('ikaros_connectorService');
        $serviceSwitch = $this->get('ikaros_switchService');
        $serviceFilter = $this->get('ikaros_filterService');
        $serviceRotDevElaps = $this->get('ikaros_rotDevElapsService');
        $serviceTubeWave = $this->get('ikaros_tubeWaveService');

        $resistors = $serviceResistor->getActiveResistors($id);
        $capacitors = $serviceCapacitor->getActiveCapacitors($id);
        $fuses = $serviceFuse->getActiveFuses($id);
        $connections = $serviceConnection->getActiveConnections($id);
        $conSoc = $serviceConnector->getActiveConnectorsSoc($id);
        $conGen = $serviceConnector->getActiveConnectorsGen($id);
        $switches = $serviceSwitch->getActiveSwitches($id);
        $filters = $serviceFilter->getActiveFilters($id);
        $rotElaps = $serviceRotDevElaps->getActiveRotDevElaps($id);
        $tubeWaves = $serviceTubeWave->getActiveTubeWaves($id);

//---Resistor form---------------------------------------------------------------------------

        $QualityChoices = $serviceResistor->getResQualityAll();
        $MatChoices = $serviceResistor->getResMaterialAll();

        $formResistor = $this->createForm(new ResistorForm(), array(), array('envChoices' => $envChoices ,
            'sysEnv' => $sysEnv, 'qualityChoices' => $QualityChoices , 'matChoices' => $MatChoices));

  //---Capacitor form---------------------------------------------------------------------------

        $QualityChoicesC = $serviceCapacitor->getCapQualityAll();
        $MatChoicesC = $serviceCapacitor->getCapMaterialAll();

        $formCapacitor = $this->createForm(new CapacitorForm(), array(), array('envChoices' => $envChoices ,
            'sysEnv' => $sysEnv, 'qualityChoicesC' => $QualityChoicesC , 'matChoicesC' => $MatChoicesC));

//---Fuse form---------------------------------------------------------------------------

        $formFuse = $this->createForm(new FuseForm(), array(), array('envChoices' => $envChoices , 'sysEnv' => $sysEnv));

//---Connection form---------------------------------------------------------------------------

        $conTypeChoices = $serviceConnection->getConTypeAll();

        $formConnection = $this->createForm(new ConnectionForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'conTypeChoices' => $conTypeChoices));

//---Connector - socket form---------------------------------------------------------------------------

        $conSocTypeChoices = $serviceConnector->getConSocTypeAll();

        $formConSoc = $this->createForm(new ConnectorSocForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'conSocTypeChoices' => $conSocTypeChoices));

//---Connector - general form---------------------------------------------------------------------------

        $conGenTypeChoices = $serviceConnector->getConGenTypeAll();

        $formConGen = $this->createForm(new ConnectorGenForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'conGenTypeChoices' => $conGenTypeChoices));

//---Switch form---------------------------------------------------------------------------

        $swTypeChoices = $serviceSwitch->getSwitchTypeAll();

        $formSwitch = $this->createForm(new SwitchForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'swTypeChoices' => $swTypeChoices));

//---Filter form---------------------------------------------------------------------------

        $filterTypeChoices = $serviceFilter->getFilterTypeAll();

        $formFilter = $this->createForm(new FilterForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv, 'filterTypeChoices' => $filterTypeChoices));

//---Rot dev elaps form---------------------------------------------------------------------------

        $formRotElaps = $this->createForm(new RotDevElapsForm(), array(),
            array('envChoices' => $envChoices , 'sysEnv' => $sysEnv));

//---TubeWave form---------------------------------------------------------------------------

        $formTubeWave = $this->createForm(new TubeWaveForm(), array(),array('envChoices' => $envChoices , 'sysEnv' => $sysEnv));

//---render template--------------------------------------------------------------------------

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
            200,
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

        $serviceSystem = $this->get('ikaros_systemService');
        $envChoices = $serviceSystem->getEnvChoices();

        $servicePCB = $this->get('ikaros_pcbService');
        $part = $servicePart->getItem($id);
        $systemID = $part->getPCBID()->getSystemID()->getIDSystem();

        switch($type) {
            case "rezistor":
                $serviceRes = $this->get('ikaros_resistorService');

                $res = $serviceRes->getItem($id);
                $QualityChoices = $serviceRes->getResQualityAll();
                $MatChoices = $serviceRes->getResMaterialAll();

                $resistorArray = $res->to_array();
                $formResistor = $this->createForm(new ResistorForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'qualityChoices' => $QualityChoices ,
                        'matChoices' => $MatChoices, 'resistor' => $resistorArray));

                return array('type'=> $type, 'formResistor' => $formResistor->createView(),'IDPart' => $res->getIDPart(),
                    'Lam' => $res->getLam(), 'Label' => $res->getLabel(), 'systemID' => $systemID);

            case 'pojistka':
                $serviceFuse = $this->get('ikaros_fuseService');
                $fuse = $serviceFuse->getItem($id);

                $fuseArray = $fuse->to_array();
                $formFuse = $this->createForm(new FuseForm(), array(), array('envChoices' => $envChoices , 'sysEnv' => "", 'fuse' => $fuseArray));

                return array('type'=> $type, 'formFuse' => $formFuse->createView(),'IDPart' => $fuse->getIDPart(),
                    'Label' => $fuse->getLabel(), 'Lam' => $fuse->getLam(),'systemID' => $systemID);

            case 'kondenzátor':
                $serviceCap = $this->get('ikaros_capacitorService');

                $capacitor = $serviceCap->getItem($id);
                $QualityChoicesC = $serviceCap->getCapQualityAll();
                $MatChoicesC = $serviceCap->getCapMaterialAll();

                $capacitorArray = $capacitor->to_array();
                $formCapacitor = $this->createForm(new CapacitorForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'qualityChoicesC' => $QualityChoicesC ,
                        'matChoicesC' => $MatChoicesC, 'capacitor' => $capacitorArray));

                return array('type'=> $type, 'formCapacitor' => $formCapacitor->createView(),'IDPart' => $capacitor->getIDPart(),
                    'Label' => $capacitor->getLabel(), 'Lam' => $capacitor->getLam(), 'systemID' => $systemID);

            case 'propojení':
                $serviceConnection = $this->get('ikaros_connectionService');
                $con = $serviceConnection->getItem($id);
                $conTypeChoices = $serviceConnection->getConTypeAll();

                $conArray = $con->to_array();

                $formConnection = $this->createForm(new ConnectionForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'conTypeChoices' => $conTypeChoices,
                        'connection' => $conArray));

                return array('type'=> $type, 'formConnection' => $formConnection->createView(),'IDPart' => $con->getIDPart(),
                    'Label' => $con->getLabel(), 'Lam' => $con->getLam(), 'systemID' => $systemID);

            case 'konektor, soket':
                $serviceConSoc = $this->get('ikaros_connectorService');
                $connectorSoc = $serviceConSoc->getItemSoc($id);
                $conSocTypeChoices = $serviceConSoc->getConSocTypeAll();

                $connectorSocArray = $connectorSoc->to_array();

                $formConSoc = $this->createForm(new ConnectorSocForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'conSocTypeChoices' => $conSocTypeChoices, 'connectorSoc' => $connectorSocArray));

                return array('type'=> $type, 'formConSoc' => $formConSoc->createView(),
                    'IDPart' => $connectorSoc->getIDPart(), 'Label' => $connectorSoc->getLabel(), 'Lam' => $connectorSoc->getLam(),
                    'systemID' => $systemID);

            case 'konektor, obecný':
                $serviceConGen = $this->get('ikaros_connectorService');
                $connectorGen = $serviceConGen->getItemGen($id);
                $conGenTypeChoices = $serviceConGen->getConGenTypeAll();

                $connectorGenArray = $connectorGen->to_array();

                $formConGen = $this->createForm(new ConnectorGenForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'conGenTypeChoices' => $conGenTypeChoices, 'connectorGen' => $connectorGenArray));

                return array('type'=> $type, 'formConGen' => $formConGen->createView(),
                    'IDPart' => $connectorGen->getIDPart(), 'Label' => $connectorGen->getLabel(), 'Lam' => $connectorGen->getLam(),
                    'systemID' => $systemID);

            case 'spínač':
                $serviceSwitch = $this->get('ikaros_switchService');
                $switch = $serviceSwitch->getItem($id);
                $swTypeChoices = $serviceSwitch->getSwitchTypeAll();

                $switchArray = $switch->to_array();

                $formSwitch = $this->createForm(new SwitchForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'swTypeChoices' => $swTypeChoices, 'switch' => $switchArray));

                return array('type'=> $type, 'formSwitch' => $formSwitch->createView(),
                    'IDPart' => $switch->getIDPart(), 'Label' => $switch->getLabel(), 'Lam' => $switch->getLam(),'systemID' => $systemID);

            case 'filtr':
                $serviceFilter = $this->get('ikaros_filterService');
                $filter = $serviceFilter->getItem($id);
                $filterTypeChoices = $serviceFilter->getFilterTypeAll();

                $filterArray = $filter->to_array();

                $formFilter = $this->createForm(new FilterForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'filterTypeChoices' => $filterTypeChoices, 'filter' => $filterArray));

                return array('type'=> $type, 'formFilter' => $formFilter->createView(),
                    'IDPart' => $filter->getIDPart(), 'Label' => $filter->getLabel(), 'Lam' => $filter->getLam(),
                    'systemID' => $systemID);

            case 'měřič motohodin':
                $serviceRotElaps = $this->get('ikaros_rotDevElapsService');
                $rotElaps = $serviceRotElaps->getItem($id);
                $rotElapsArray = $rotElaps->to_array();

                $formRotElaps = $this->createForm(new RotDevElapsForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'rotElaps' => $rotElapsArray));

                return array('type'=> $type, 'formRotElaps' => $formRotElaps->createView(),
                    'IDPart' => $rotElaps->getIDPart(), 'Label' => $rotElaps->getLabel(), 'Lam' => $rotElaps->getLam(),
                    'systemID' => $systemID);

            case 'permaktron':
                $serviceTubeWave = $this->get('ikaros_tubeWaveService');
                $tubeWave = $serviceTubeWave->getItem($id);
                $tubeWaveArray = $tubeWave->to_array();

                $formTubeWave = $this->createForm(new TubeWaveForm(), array(),
                    array('envChoices' => $envChoices , 'sysEnv' => "", 'tubeWave' => $tubeWaveArray));

                return array('type'=> $type, 'formTubeWave' => $formTubeWave->createView(),
                    'IDPart' => $tubeWave->getIDPart(), 'Label' => $tubeWave->getLabel(), 'Lam' => $tubeWave->getLam(),
                    'systemID' => $systemID);
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
