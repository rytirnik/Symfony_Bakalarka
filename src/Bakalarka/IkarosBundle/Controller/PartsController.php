<?php

namespace Bakalarka\IkarosBundle\Controller;

use Bakalarka\IkarosBundle\Entity\ConnectorGen;
use Bakalarka\IkarosBundle\Entity\Crystal;
use Bakalarka\IkarosBundle\Entity\DiodeRF;
use Bakalarka\IkarosBundle\Entity\Filter;
use Bakalarka\IkarosBundle\Entity\InductiveDev;
use Bakalarka\IkarosBundle\Entity\Memory;
use Bakalarka\IkarosBundle\Entity\Microcircuit;
use Bakalarka\IkarosBundle\Entity\Optoelectronics;
use Bakalarka\IkarosBundle\Entity\RotDevElaps;
use Bakalarka\IkarosBundle\Entity\Switches;
use Bakalarka\IkarosBundle\Entity\TransistorBiLF;
use Bakalarka\IkarosBundle\Entity\TransistorFetLF;
use Bakalarka\IkarosBundle\Entity\TubeWave;

use Bakalarka\IkarosBundle\Forms\CrystalForm;
use Bakalarka\IkarosBundle\Forms\DiodeLFForm;
use Bakalarka\IkarosBundle\Forms\DiodeRFForm;
use Bakalarka\IkarosBundle\Forms\InductiveForm;
use Bakalarka\IkarosBundle\Forms\MemoryForm;
use Bakalarka\IkarosBundle\Forms\MicrocircuitForm;
use Bakalarka\IkarosBundle\Forms\OptoForm;
use Bakalarka\IkarosBundle\Forms\TransistorBiLFForm;
use Bakalarka\IkarosBundle\Forms\TransistorFetLFForm;
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
use Bakalarka\IkarosBundle\Entity\DiodeLF;

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
        $serviceDiode = $this->get('ikaros_diodeService');
        $serviceOpto = $this->get('ikaros_optoService');
        $serviceCrystal = $this->get('ikaros_crystalService');
        $serviceTransistorBiLF = $this->get('ikaros_transistorBiLFService');
        $serviceTransistorFetLF = $this->get('ikaros_transistorFetLFService');
        $serviceInductive = $this->get('ikaros_inductiveService');
        $serviceMicrocircuit = $this->get('ikaros_microcircuitService');
        $serviceDiodeRF = $this->get('ikaros_diodeRFService');
        $serviceMemory = $this->get('ikaros_memoryservice');

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
        $diodesLF = $serviceDiode->getActiveDiodesLF($id);
        $optos = $serviceOpto->getActiveOptos($id);
        $crystals = $serviceCrystal->getActiveCrystals($id);
        $transistorsBiLF = $serviceTransistorBiLF->getActiveTransistors($id);
        $transistorsFetLF = $serviceTransistorFetLF->getActiveTransistors($id);
        $inductives = $serviceInductive->getActiveInductives($id);
        $microcircuits = $serviceMicrocircuit->getActiveMicrocircuits($id);
        $diodesRF = $serviceDiodeRF->getActiveDiodesRF($id);
        $memories = $serviceMemory->getActiveMemories($id);


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

//---DiodesLF form---------------------------------------------------------------------------

        $DiodeQualityChoices = $serviceDiode->getDiodeLFQualityChoices();
        $DiodeAppChoices = $serviceDiode->getDiodeLFAppChoices();
        $DiodeCCChoices = $serviceDiode->getContactConstructionChoices();

        $formDiodeLF = $this->createForm(new DiodeLFForm(), array(), array('envChoices' => $envChoices ,
            'sysEnv' => $sysEnv, 'qualityChoices' => $DiodeQualityChoices , 'appChoices' => $DiodeAppChoices,
            'contactChoices' => $DiodeCCChoices));

//---Optoelectronics form---------------------------------------------------------------------------

        $optoQualChoices = $serviceOpto->getOptoQualityChoices();
        $optoAppChoices = $serviceOpto->getOptoAppChoices();

        $formOpto = $this->createForm(new OptoForm(), array(), array('envChoices' => $envChoices ,
            'sysEnv' => $sysEnv, 'qualityChoices' => $optoQualChoices , 'appChoices' => $optoAppChoices));

//---Crystal form---------------------------------------------------------------------------

        $formCrystal = $this->createForm(new CrystalForm(), array(), array('envChoices' => $envChoices ,
            'sysEnv' => $sysEnv));

//---Transistors Bipolar LF form------------------------------------------------------------

        $transistorQualChoices = $serviceTransistorBiLF->getQualityChoices();

        $formTransistorBiLF = $this->createForm(new TransistorBiLFForm(), array(), array('envChoices' => $envChoices ,
            'sysEnv' => $sysEnv, 'qualityChoices' => $transistorQualChoices ));

//---Transistors FET LF form------------------------------------------------------------

        $transistorFetQualChoices = $serviceTransistorFetLF->getQualityChoices();

        $formTransistorFetLF = $this->createForm(new TransistorFetLFForm(), array(), array('envChoices' => $envChoices ,
            'sysEnv' => $sysEnv, 'qualityChoices' => $transistorFetQualChoices ));

//---Inductive device form------------------------------------------------------------

        $inductiveTransDescChoices = $serviceInductive->getTransformersDescChoices();
        $inductiveTransQuality = $serviceInductive->getQualityTransChoices();

        $formInductive = $this->createForm(new InductiveForm(), array(), array('envChoices' => $envChoices ,
            'sysEnv' => $sysEnv, 'descChoices' => $inductiveTransDescChoices, 'qualityChoices' => $inductiveTransQuality ));

//---Microcircuits form------------------------------------------------------------

        $microQualChoices = $serviceMicrocircuit->getQualityChoices();
        $microPackageChoices = $serviceMicrocircuit->getPackageTypeChoices();
        $microTechChoices = $serviceMicrocircuit->getTechnologyChoices();

        $microPackageTypeAll = $serviceMicrocircuit->getPackageTypeAll();
        $microQualityAll = $serviceMicrocircuit->getQualityAll();

        $formMicrocircuit = $this->createForm(new MicrocircuitForm(), array(), array('envChoices' => $envChoices ,
            'sysEnv' => $sysEnv, 'packageChoices' => $microPackageChoices, 'qualityChoices' => $microQualChoices,
            'techChoices' => $microTechChoices));

//---DiodesRF form---------------------------------------------------------------------------

        $DiodeRFQualityChoices = $serviceDiodeRF->getQualityChoices();
        $DiodeRFAppChoices = $serviceDiodeRF->getApplicationChoices();
        $DiodeRFTypeChoices = $serviceDiodeRF->getTypeChoices();

        $diodeRFtypeAll = $serviceDiodeRF->getTypesAll();

        $formDiodeRF = $this->createForm(new DiodeRFForm(), array(), array('envChoices' => $envChoices ,
            'sysEnv' => $sysEnv, 'qualityChoices' => $DiodeRFQualityChoices , 'appChoices' => $DiodeRFAppChoices,
            'typeChoices' => $DiodeRFTypeChoices));

//---Memory form------------------------------------------------------------

        $memoryQualChoices = $serviceMemory->getQualityChoices();
        $memoryPackageChoices = $serviceMemory->getPackageTypeChoices();
        $memoryTypeChoices = $serviceMemory->getTypeChoices();
        $memoryEccChoices = $serviceMemory->getEccChoices();

        $formMemory = $this->createForm(new MemoryForm(), array(), array('envChoices' => $envChoices ,
            'sysEnv' => $sysEnv, 'packageChoices' => $memoryPackageChoices, 'qualityChoices' => $memoryQualChoices,
            'typeChoices' => $memoryTypeChoices, 'eccChoices' => $memoryEccChoices));

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
            'formDiodeLF' => $formDiodeLF->createView(), 'diodesLF' => $diodesLF,
            'formOpto' => $formOpto->createView(), 'optos' => $optos,
            'formCrystal' => $formCrystal->createView(), 'crystals' => $crystals,
            'formTransistorBiLF' => $formTransistorBiLF->createView(), 'transistorsBiLF' => $transistorsBiLF,
            'formTransistorFetLF' => $formTransistorFetLF->createView(), 'transistorsFetLF' => $transistorsFetLF,
            'formInductive' => $formInductive->createView(), 'inductives' => $inductives,
            'transDescOptions' => $serviceInductive->getTransformersDescChoices(1), 'coilsDescOptions' => $serviceInductive->getCoilsDescChoices(1),
            'transQualityChoices' => $serviceInductive->getQualityTransChoices(1), 'coilsQualityChoices' => $serviceInductive->getQualityCoilsChoices(1),
            'formMicrocircuit' => $formMicrocircuit->createView(), 'microcircuits' => $microcircuits,
            'microPackageAll' => $microPackageTypeAll, 'microQualityAll' => $microQualityAll,
            'formDiodeRF' => $formDiodeRF->createView(), 'diodesRF' => $diodesRF, 'diodeTypesAll' => $diodeRFtypeAll,
            'formMemory' => $formMemory->createView(), 'memories' => $memories,
            'memoryMosTypeChoices' => $serviceMemory->getTypeChoices("MOS",1), 'memoryBipolarTypeChoices' => $serviceMemory->getTypeChoices("Bipolar", 1),

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

        $serviceResistor = $this->get('ikaros_resistorService');
        $servicePart = $this->get('ikaros_partService');
        //$e = $service->setLams($res, $id);

        $lambda = $serviceResistor->calculateLam($res, $id);
        $e = $servicePart->setLams($lambda, $res, $id);

        $qualR = $serviceResistor->getResQuality($res->getQuality());

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

        $serviceCapacitor = $this->get('ikaros_capacitorService');
        $servicePart = $this->get('ikaros_partService');

        //$lambda = $serviceCapacitor->lamCapacitor($cap, $id);
        $lambda = $serviceCapacitor->calculateLam($cap, $id);
        $e = $servicePart->setLams($lambda, $cap, $id);

        //$e = $service->setLams($cap, $id);
        $qualC = $serviceCapacitor->getCapQuality($cap->getQuality());

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
        $lambda = $service->calculateLam($fuse);

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
        $lambda = $service->calculateLam($con);
        $conType = $con->getConnectionType();

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
        $lambda = $service->lamConGen($conGen, $id);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $conGen, $id);

        //$service = $this->get('ikaros_connectorService');
        //$e = $service->setLamsConGen($conGen, $id);
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
        $lambda = $service->calculateLam($switch);

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
        $lambda = $service->calculateLam($filter);

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
        $lambda = $service->calculateLam($rotElaps);

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
        $lambda = $service->calculateLam($tubeWave);

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
     * @Route("/newDiodeLF", name="newDiodeLF")
     * @Template()
     */
    public function newDiodeLFAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->diodeLFForm;

        $diode = new DiodeLF();
        $diode->setParams($obj);

        $serviceDiode = $this->get('ikaros_diodeService');
        $lambda = $serviceDiode->calculateLam($diode, $id);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $diode, $id);

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
                'Label' => $diode->getLabel(),
                'Lam' => $diode->getLam(),
                'VoltageRated' => $diode->getVoltageRated(),
                'VoltageApplied' => $diode->getVoltageApplied(),
                'DPTemp' => $diode->getDPTemp(),
                'PassiveTemp' => $diode->getPassiveTemp(),
                'Application' => $diode->getApplication(),
                'ContactConstruction' => $serviceDiode->getContactConstructionDesc($diode->getContactConstruction()),
                'Quality' => $diode->getQuality(),
                'Environment' => $diode->getEnvironment(),
                'idP' => $diode->getIDPart(),
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }

//====================================================================================================================
    /**
     * @Route("/newOpto", name="newOpto")
     * @Template()
     */
    public function newOptoAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->optoForm;

        $opto = new Optoelectronics();
        $opto->setParams($obj);

        $serviceOpto = $this->get('ikaros_optoService');
        $lambda = $serviceOpto->calculateLam($opto, $id);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $opto, $id);

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
                'Label' => $opto->getLabel(),
                'Lam' => $opto->getLam(),
                'DPTemp' => $opto->getDPTemp(),
                'PassiveTemp' => $opto->getPassiveTemp(),
                'Application' => $opto->getApplication(),
                'Quality' => $opto->getQuality(),
                'Environment' => $opto->getEnvironment(),
                'idP' => $opto->getIDPart(),
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }
//====================================================================================================================
    /**
     * @Route("/newCrystal", name="newCrystal")
     * @Template()
     */
    public function newCrystalAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->crystalForm;

        $crystal = new Crystal();
        $crystal->setParams($obj);

        $serviceCrystal = $this->get('ikaros_crystalService');
        $lambda = $serviceCrystal->calculateLam($crystal);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $crystal, $id);

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
                'Label' => $crystal->getLabel(),
                'Lam' => $crystal->getLam(),
                'Type' => $crystal->getType(),
                'CasePart' => $crystal->getCasePart(),
                'Frequency' => $crystal->getFrequency(),
                'Quality' => $crystal->getQuality(),
                'Environment' => $crystal->getEnvironment(),
                'idP' => $crystal->getIDPart(),
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }

//====================================================================================================================
    /**
     * @Route("/newTransistorBiLF", name="newTransistorBiLF")
     * @Template()
     */
    public function newTransistorBiLFAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->transistorBiLFForm;

        $transistor = new TransistorBiLF();
        $transistor->setParams($obj);

        $serviceTransistor = $this->get('ikaros_transistorBiLFService');
        $lambda = $serviceTransistor->calculateLam($transistor, $id);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $transistor, $id);

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
                'Label' => $transistor->getLabel(),
                'Lam' => $transistor->getLam(),
                'Type' => $transistor->getType(),
                'CasePart' => $transistor->getCasePart(),
                'Application' => $transistor->getApplication(),
                'Quality' => $transistor->getQuality(),
                'Environment' => $transistor->getEnvironment(),
                'PowerRated' => $transistor->getPowerRated(),
                'VoltageCE' => $transistor->getVoltageCE(),
                'VoltageCEO' => $transistor->getVoltageCEO(),
                'TempDissipation' => $transistor->getTempDissipation(),
                'TempPassive' => $transistor->getTempPassive(),
                'idP' => $transistor->getIDPart(),
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }
//====================================================================================================================
    /**
     * @Route("/newTransistorFetLF", name="newTransistorFetLF")
     * @Template()
     */
    public function newTransistorFetLFAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->transistorFetLFForm;

        $transistor = new TransistorFetLF();
        $transistor->setParams($obj);

        $serviceTransistor = $this->get('ikaros_transistorFetLFService');
        $lambda = $serviceTransistor->calculateLam($transistor, $id);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $transistor, $id);

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
                'Label' => $transistor->getLabel(),
                'Lam' => $transistor->getLam(),
                'Type' => $transistor->getType(),
                'CasePart' => $transistor->getCasePart(),
                'Application' => $transistor->getApplication(),
                'Quality' => $transistor->getQuality(),
                'Environment' => $transistor->getEnvironment(),
                'PowerRated' => $transistor->getPowerRated(),
                'Technology' => $transistor->getTechnology(),
                'TempDissipation' => $transistor->getTempDissipation(),
                'TempPassive' => $transistor->getTempPassive(),
                'idP' => $transistor->getIDPart(),
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }

//====================================================================================================================
    /**
     * @Route("/newInductive", name="newInductive")
     * @Template()
     */
    public function newInductiveAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->inductiveForm;

        $inductive = new InductiveDev();
        $inductive->setParams($obj);

        $serviceInductive= $this->get('ikaros_inductiveService');
        $lambda = $serviceInductive->calculateLam($inductive, $id);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $inductive, $id);

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
                'Label' => $inductive->getLabel(),
                'Lam' => $inductive->getLam(),
                'Type' => $inductive->getType(),
                'CasePart' => $inductive->getCasePart(),
                'DevType' => $inductive->getDevType(),
                'Description' => $inductive->getDescription(),
                'Quality' => $inductive->getQuality(),
                'Environment' => $inductive->getEnvironment(),
                'PowerLoss' => $inductive->getPowerLoss(),
                'Weight' => $inductive->getWeight(),
                'Surface' => $inductive->getSurface(),
                'TempDissipation' => $inductive->getTempDissipation(),
                'TempPassive' => $inductive->getTempPassive(),
                'idP' => $inductive->getIDPart(),
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }
//====================================================================================================================
    /**
     * @Route("/newMicrocircuit", name="newMicrocircuit")
     * @Template()
     */
    public function newMicrocircuitAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->microcircuitForm;

        $micro = new Microcircuit();
        $micro->setParams($obj);

        $serviceMicro = $this->get('ikaros_microcircuitService');
        $lambda = $serviceMicro->calculateLam($micro, $id);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $micro, $id);

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
                'Label' => $micro->getLabel(),
                'Lam' => $micro->getLam(),
                'Type' => $micro->getType(),
                'CasePart' => $micro->getCasePart(),
                'Application' => $micro->getApplication(),
                'Description' => $micro->getDescription(),
                'Quality' => $micro->getQuality(),
                'Environment' => $micro->getEnvironment(),
                'PinCount' => $micro->getPinCount(),
                'GateCount' => $micro->getGateCount(),
                'ProductionYears' => $micro->getProductionYears(),
                'TempDissipation' => $micro->getTempDissipation(),
                'Technology' => $micro->getTechnology(),
                'TempPassive' => $micro->getTempPassive(),
                'PackageType' => $micro->getPackageType(),
                'idP' => $micro->getIDPart(),
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }

//====================================================================================================================
    /**
     * @Route("/newDiodeRF", name="newDiodeRF")
     * @Template()
     */
    public function newDiodeRFAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->diodeRFForm;

        $diode = new DiodeRF();
        $diode->setParams($obj);

        $serviceDiode = $this->get('ikaros_diodeRFService');
        $lambda = $serviceDiode->calculateLam($diode, $id);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $diode, $id);

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
                'Label' => $diode->getLabel(),
                'Lam' => $diode->getLam(),
                'PowerRated' => $diode->getPowerRated(),
                'TempDissipation' => $diode->getTempDissipation(),
                'TempPassive' => $diode->getTempPassive(),
                'Application' => $diode->getApplication(),
                'DiodeType' => $diode->getDiodeType(),
                'Quality' => $diode->getQuality(),
                'Environment' => $diode->getEnvironment(),
                'idP' => $diode->getIDPart(),
            )),
            200,
            array(
                'Content-Type' => 'application/json; charset=utf-8'
            )
        );
    }
//====================================================================================================================
    /**
     * @Route("/newMemory", name="newMemory")
     * @Template()
     */
    public function newMemoryAction() {
        $post = $this->get('request')->request;
        $id = $post->get('id');
        $formData = $post->get('formData');

        $objF = json_decode($formData);
        $obj = $objF->memoryForm;

        $memory = new Memory();
        $memory->setParams($obj);

        $serviceMemory = $this->get('ikaros_memoryService');
        $lambda = $serviceMemory->calculateLam($memory, $id);

        $serviceParts = $this->get('ikaros_partService');
        $e = $serviceParts->setLams($lambda, $memory, $id);

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
                'Label' => $memory->getLabel(),
                'Lam' => $memory->getLam(),
                'Type' => $memory->getType(),
                'CasePart' => $memory->getCasePart(),
                'MemoryType' => $memory->getMemoryType(),
                'Description' => $memory->getDescription(),
                'Quality' => $memory->getQuality(),
                'Environment' => $memory->getEnvironment(),
                'PinCount' => $memory->getPinCount(),
                'CyclesCount' => $memory->getCyclesCount(),
                'ProductionYears' => $memory->getProductionYears(),
                'TempDissipation' => $memory->getTempDissipation(),
                'Technology' => $memory->getTechnology(),
                'TempPassive' => $memory->getTempPassive(),
                'PackageType' => $memory->getPackageType(),
                'ECC' => $memory->getECC(),
                'EepromOxid' => $memory->getEepromOxid(),
                'MemorySize' => $memory->getMemorySize(),
                'idP' => $memory->getIDPart(),
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

            case 'dioda, nízkofrekvenční':
                $serviceDiode = $this->get('ikaros_diodeService');
                $diodeLF = $serviceDiode->getItem($id);
                $diodeArray = $diodeLF->to_array();
                $DiodeQualityChoices = $serviceDiode->getDiodeLFQualityChoices();
                $DiodeAppChoices = $serviceDiode->getDiodeLFAppChoices();
                $DiodeCCChoices = $serviceDiode->getContactConstructionChoices();

                $formDiodeLF = $this->createForm(new DiodeLFForm(), array(), array('envChoices' => $envChoices ,
                    'sysEnv' => "", 'qualityChoices' => $DiodeQualityChoices , 'appChoices' => $DiodeAppChoices,
                    'contactChoices' => $DiodeCCChoices, 'diodeLF' => $diodeArray));

                return array('type'=> $type, 'formDiodeLF' => $formDiodeLF->createView(),
                    'IDPart' => $diodeLF->getIDPart(), 'Label' => $diodeLF->getLabel(), 'Lam' => $diodeLF->getLam(),
                    'systemID' => $systemID);
            case 'optoelektronika':
                $serviceOpto = $this->get('ikaros_optoService');
                $opto = $serviceOpto->getItem($id);
                $optoArray = $opto->to_array();

                $optoQualChoices = $serviceOpto->getOptoQualityChoices();
                $optoAppChoices = $serviceOpto->getOptoAppChoices();

                $formOpto = $this->createForm(new OptoForm(), array(), array('envChoices' => $envChoices ,
                    'sysEnv' => "", 'qualityChoices' => $optoQualChoices , 'appChoices' => $optoAppChoices, 'opto' => $optoArray));

                return array('type'=> $type, 'formOpto' => $formOpto->createView(),
                    'IDPart' => $opto->getIDPart(), 'Label' => $opto->getLabel(), 'Lam' => $opto->getLam(),
                    'systemID' => $systemID);
            case 'krystal':
                $serviceCrystal = $this->get('ikaros_crystalService');
                $crystal = $serviceCrystal->getItem($id);
                $crystalArray = $crystal->to_array();

                $formCrystal = $this->createForm(new CrystalForm(), array(), array('envChoices' => $envChoices ,
                    'sysEnv' => "", 'crystal' => $crystalArray));

                return array('type'=> $type, 'formCrystal' => $formCrystal->createView(),
                    'IDPart' => $crystal->getIDPart(), 'Label' => $crystal->getLabel(), 'Lam' => $crystal->getLam(),
                    'systemID' => $systemID);
            case 'tranzistor, bipolární LF':
                $serviceTransistorBiLF = $this->get('ikaros_transistorBiLFService');
                $transistor = $serviceTransistorBiLF->getItem($id);
                $transistorArray = $transistor->to_array();

                $QualChoices = $serviceTransistorBiLF->getQualityChoices();

                $formTransistorBiLF = $this->createForm(new TransistorBiLFForm(), array(), array('envChoices' => $envChoices ,
                    'sysEnv' => "", 'qualityChoices' => $QualChoices , 'transistor' => $transistorArray));

                return array('type'=> $type, 'formTransistorBiLF' => $formTransistorBiLF->createView(),
                    'IDPart' => $transistor->getIDPart(), 'Label' => $transistor->getLabel(), 'Lam' => $transistor->getLam(),
                    'systemID' => $systemID);
            case 'tranzistor, FET LF':
                $serviceTransistorFetLF = $this->get('ikaros_transistorFetLFService');
                $transistor = $serviceTransistorFetLF->getItem($id);
                $transistorArray = $transistor->to_array();

                $QualChoices = $serviceTransistorFetLF->getQualityChoices();

                $formTransistorFetLF = $this->createForm(new TransistorFetLFForm(), array(), array('envChoices' => $envChoices ,
                    'sysEnv' => "", 'qualityChoices' => $QualChoices , 'transistor' => $transistorArray));

                return array('type'=> $type, 'formTransistorFetLF' => $formTransistorFetLF->createView(),
                    'IDPart' => $transistor->getIDPart(), 'Label' => $transistor->getLabel(), 'Lam' => $transistor->getLam(),
                    'systemID' => $systemID);
            case 'indukčnost':
                $serviceInductive = $this->get('ikaros_inductiveService');
                $inductive = $serviceInductive->getItem($id);
                $inductiveArray = $inductive->to_array();

                $devType = $inductive->getDevType();
                if($devType == 'Coils') {
                    $descChoices = $serviceInductive->getCoilsDescChoices();
                    $qualityChoices = $serviceInductive->getQualityCoilsChoices();
                }
                else {
                    $descChoices = $serviceInductive->getTransformersDescChoices();
                    $qualityChoices = $serviceInductive->getQualityTransChoices();
                }

                $formInductive = $this->createForm(new InductiveForm(), array(), array('envChoices' => $envChoices ,
                    'sysEnv' => "", 'descChoices' => $descChoices, 'qualityChoices' => $qualityChoices,
                    'inductive' => $inductiveArray));

                return array('type'=> $type, 'formInductive' => $formInductive->createView(),
                    'IDPart' => $inductive->getIDPart(), 'Label' => $inductive->getLabel(), 'Lam' => $inductive->getLam(),
                    'systemID' => $systemID, 'transDescOptions' => $serviceInductive->getTransformersDescChoices(1),
                    'coilsDescOptions' => $serviceInductive->getCoilsDescChoices(1),'transQualityChoices' => $serviceInductive->getQualityTransChoices(1),
                    'coilsQualityChoices' => $serviceInductive->getQualityCoilsChoices(1),
                );
            case 'integrovaný obvod':
                $serviceMicro = $this->get('ikaros_microcircuitservice');
                $micro = $serviceMicro->getItem($id);
                $microArray = $micro->to_array();

                $packageChoices = $serviceMicro->getPackageTypeChoices();
                $techChoices = $serviceMicro->getTechnologyChoices();
                $qualityChoices = $serviceMicro->getQualityChoices();

                $formMicrocircuit = $this->createForm(new MicrocircuitForm(), array(), array('envChoices' => $envChoices ,
                    'sysEnv' => "", 'techChoices' => $techChoices, 'qualityChoices' => $qualityChoices, 'packageChoices' => $packageChoices,
                    'microcircuit' => $microArray));

                return array('type'=> $type, 'formMicrocircuit' => $formMicrocircuit->createView(),
                    'IDPart' => $micro->getIDPart(), 'Label' => $micro->getLabel(), 'Lam' => $micro->getLam(),
                    'systemID' => $systemID,
                );
            case 'dioda, vysokofrekvenční':
                $serviceDiode = $this->get('ikaros_dioderfservice');
                $diode = $serviceDiode->getItem($id);
                $diodeArray = $diode->to_array();

                $typeChoices = $serviceDiode->getTypeChoices();
                $appChoices = $serviceDiode->getApplicationChoices();
                $qualityChoices = $serviceDiode->getQualityChoices();

                $formDiodeRF = $this->createForm(new DiodeRFForm(), array(), array('envChoices' => $envChoices ,
                    'sysEnv' => "", 'typeChoices' => $typeChoices, 'qualityChoices' => $qualityChoices, 'appChoices' => $appChoices,
                    'diodeRF' => $diodeArray));

                return array('type'=> $type, 'formDiodeRF' => $formDiodeRF->createView(),
                    'IDPart' => $diode->getIDPart(), 'Label' => $diode->getLabel(), 'Lam' => $diode->getLam(),
                    'systemID' => $systemID,
                );
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

                //$sysTemp = $system->getTemp();
                //$res->setTemp($sysTemp + $res->getDPTemp() + $res->getPassiveTemp());

                $oldLam = $res->getLam();

                $lambda = $serviceResistor->calculateLam($res, $pcb->getIDPCB());
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
                $lambda = $serviceFuse->calculateLam($fuse);

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

                //$sysTemp = $system->getTemp();
                //$cap->setTemp($sysTemp + $cap->getPassiveTemp());

                $oldLam = $cap->getLam();
                //$lambda = $serviceCapacitor->lamCapacitor($cap, $pcb->getIDPCB());
                $lambda = $serviceCapacitor->calculateLam($cap, $pcb->getIDPCB());
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
                $lambda = $serviceConnection->calculateLam($con);

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
                $lambda = $serviceConGen->lamConGen($conGen, $pcb->getIDPCB());

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
                $lambda = $serviceSwitch->calculateLam($switch);

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
                $lambda = $serviceFilter->calculateLam($filter);

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
                $lambda = $serviceRotDevElaps->calculateLam($rotElaps);

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
                $lambda = $serviceTubeWave->calculateLam($tubeWave);

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
            case 'diodeLF':
                $obj = $objF->diodeLFForm;
                $serviceDiode = $this->get('ikaros_diodeService');
                $diodeLF = $serviceDiode->getItem($id);
                $diodeLF->setParams($obj);

                $oldLam = $diodeLF->getLam();
                $lambda = $serviceDiode->calculateLam($diodeLF, $pcb->getIDPCB());

                $e = $servicePart->setLams($lambda, $diodeLF, -1, $oldLam);

                if($e == "")
                    return new Response(
                        json_encode(array(
                            'Label' => $diodeLF->getLabel(),
                            'Lam' => $diodeLF->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
            case 'optoelectronics':
                $obj = $objF->optoForm;
                $serviceOpto = $this->get('ikaros_optoService');
                $opto = $serviceOpto->getItem($id);
                $opto->setParams($obj);

                $oldLam = $opto->getLam();
                $lambda = $serviceOpto->calculateLam($opto, $pcb->getIDPCB());

                $e = $servicePart->setLams($lambda, $opto, -1, $oldLam);

                if($e == "")
                    return new Response(
                        json_encode(array(
                            'Label' => $opto->getLabel(),
                            'Lam' => $opto->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
            case 'crystal':
                $obj = $objF->crystalForm;
                $serviceCrystal = $this->get('ikaros_crystalService');
                $crystal = $serviceCrystal->getItem($id);
                $crystal->setParams($obj);

                $oldLam = $crystal->getLam();
                $lambda = $serviceCrystal->calculateLam($crystal);

                $e = $servicePart->setLams($lambda, $crystal, -1, $oldLam);

                if($e == "")
                    return new Response(
                        json_encode(array(
                            'Label' => $crystal->getLabel(),
                            'Lam' => $crystal->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
            case 'transistorBiLF':
                $obj = $objF->transistorBiLFForm;
                $serviceTransistorBiLF = $this->get('ikaros_transistorBiLFService');
                $transistor = $serviceTransistorBiLF->getItem($id);
                $transistor->setParams($obj);

                $oldLam = $transistor->getLam();
                $lambda = $serviceTransistorBiLF->calculateLam($transistor, $pcb->getIDPCB());

                $e = $servicePart->setLams($lambda, $transistor, -1, $oldLam);

                if($e == "")
                    return new Response(
                        json_encode(array(
                            'Label' => $transistor->getLabel(),
                            'Lam' => $transistor->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
            case 'transistorFetLF':
                $obj = $objF->transistorFetLFForm;
                $serviceTransistorFetLF = $this->get('ikaros_transistorFetLFService');
                $transistor = $serviceTransistorFetLF->getItem($id);
                $transistor->setParams($obj);

                $oldLam = $transistor->getLam();
                $lambda = $serviceTransistorFetLF->calculateLam($transistor, $pcb->getIDPCB());

                $e = $servicePart->setLams($lambda, $transistor, -1, $oldLam);

                if($e == "")
                    return new Response(
                        json_encode(array(
                            'Label' => $transistor->getLabel(),
                            'Lam' => $transistor->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
            case 'inductive':
                $obj = $objF->inductiveForm;
                $serviceInductive = $this->get('ikaros_inductiveService');
                $inductive = $serviceInductive->getItem($id);
                $inductive->setParams($obj);

                $oldLam = $inductive->getLam();
                $lambda = $serviceInductive->calculateLam($inductive, $pcb->getIDPCB());

                $e = $servicePart->setLams($lambda, $inductive, -1, $oldLam);

                if($e == "")
                    return new Response(
                        json_encode(array(
                            'Label' => $inductive->getLabel(),
                            'Lam' => $inductive->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
            case 'microcircuit':
                $obj = $objF->microcircuitForm;
                $serviceMicro = $this->get('ikaros_microcircuitservice');
                $micro = $serviceMicro->getItem($id);
                $micro->setParams($obj);

                $oldLam = $micro->getLam();
                $lambda = $serviceMicro->calculateLam($micro, $pcb->getIDPCB());

                $e = $servicePart->setLams($lambda, $micro, -1, $oldLam);

                if($e == "")
                    return new Response(
                        json_encode(array(
                            'Label' => $micro->getLabel(),
                            'Lam' => $micro->getLam()
                        )),
                        200,
                        array(
                            'Content-Type' => 'application/json; charset=utf-8'
                        )
                    );
            case 'diodeRF':
                $obj = $objF->diodeRFForm;
                $serviceDiodeRF = $this->get('ikaros_dioderfservice');
                $diode = $serviceDiodeRF->getItem($id);
                $diode->setParams($obj);

                $oldLam = $diode->getLam();
                $lambda = $serviceDiodeRF->calculateLam($diode, $pcb->getIDPCB());

                $e = $servicePart->setLams($lambda, $diode, -1, $oldLam);

                if($e == "")
                    return new Response(
                        json_encode(array(
                            'Label' => $diode->getLabel(),
                            'Lam' => $diode->getLam()
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
