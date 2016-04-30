<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MemoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];

        $descChoices = array("Bipolar" => "Bipolar", "MOS" => "MOS");
        $oxidChoices = array("Flotox" => "Flotox", "Textured-Poly" => "Textured-Poly");
        $typeChoices = $options['typeChoices'];
        $packageChoices = $options['packageChoices'];
        $qualityChoices = $options['qualityChoices'];
        $eccChoices = $options['eccChoices'];

        if($sysEnv) {
            $builder
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
                ->add('Description', 'choice', array(
                    'required' => true,
                    'label' => 'Popis',
                    'choices' => $descChoices,
                    'data' => "MOS"
                ))
                ->add('MemoryType', 'choice', array(
                    'required' => true,
                    'label' => 'Druh paměti',
                    'choices' => $typeChoices,
                    'data' => "ROM"
                ))
                ->add('MemorySize', 'number', array(
                    'required' => true,
                    'label' => 'Velikost paměti [KBits]',
                    'data' => 1
                ))
                ->add('PackageType', 'choice', array(
                    'required' => true,
                    'label' => 'Provedení pouzdra',
                    'choices' => $packageChoices,
                ))
                ->add('PinCount', 'number', array(
                    'required' => true,
                    'label' => 'Počet vývodů',
                    'error_bubbling' => true,
                    'data' => 1
                ))
                ->add('ProductionYears', 'number', array(
                    'required' => true,
                    'label' => 'Doba výroby [roky]',
                    'error_bubbling' => true,
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => $qualityChoices,
                    'data' => "Worst case",
                ))
                ->add('TempDissipation', 'number', array(
                    'required' => true,
                    'label' => 'Oteplení ztrát. výkonem [°C]',
                    'error_bubbling' => true,
                    'data' => 0
                ))
                ->add('TempPassive', 'number', array(
                    'required' => true,
                    'label' => 'Pasivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => 0
                ))
                ->add('CyclesCount', 'number', array(
                    'required' => true,
                    'label' => 'Max. počet zápisů',
                    'data' => 1
                ))
                ->add('ECC', 'choice', array(
                    'required' => true,
                    'label' => 'ECC',
                    'choices' => $eccChoices,
                    'data' => "Worst case",
                ))
                ->add('EepromOxid', 'choice', array(
                    'required' => true,
                    'label' => 'EEPROM Oxid',
                    'choices' => $oxidChoices,
                    'data' => "Flotox"
                ));
        }
        else {
            $memory = $options['memory'];

            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $memory['Environment']
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $memory['Label']
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $memory['Type']
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $memory['CasePart']
                ))
                ->add('Description', 'choice', array(
                    'required' => true,
                    'label' => 'Popis',
                    'choices' => $descChoices,
                    'data' => $memory['Description']
                ))
                ->add('MemoryType', 'choice', array(
                    'required' => true,
                    'label' => 'Druh paměti',
                    'choices' => $typeChoices,
                    'data' => $memory['MemoryType']
                ))
                ->add('MemorySize', 'number', array(
                    'required' => true,
                    'label' => 'Velikost paměti [KBits]',
                    'data' => $memory['MemorySize']
                ))
                ->add('PackageType', 'choice', array(
                    'required' => true,
                    'label' => 'Provedení pouzdra',
                    'choices' => $packageChoices,
                    'data' => $memory['PackageType']
                ))
                ->add('PinCount', 'number', array(
                    'required' => true,
                    'label' => 'Počet vývodů',
                    'error_bubbling' => true,
                    'data' => $memory['PinCount']
                ))
                ->add('ProductionYears', 'number', array(
                    'required' => true,
                    'label' => 'Doba výroby [roky]',
                    'error_bubbling' => true,
                    'data' => $memory['ProductionYears']
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => $qualityChoices,
                    'data' => $memory['Quality']
                ))
                ->add('TempDissipation', 'number', array(
                    'required' => true,
                    'label' => 'Oteplení ztrát. výkonem [°C]',
                    'error_bubbling' => true,
                    'data' => $memory['TempDissipation']
                ))
                ->add('TempPassive', 'number', array(
                    'required' => true,
                    'label' => 'Pasivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => $memory['TempPassive']
                ))
                ->add('CyclesCount', 'number', array(
                    'required' => true,
                    'label' => 'Max. počet zápisů',
                    'data' => $memory['CyclesCount']
                ))
                ->add('ECC', 'choice', array(
                    'required' => true,
                    'label' => 'ECC',
                    'choices' => $eccChoices,
                    'data' => $memory['ECC']
                ))
                ->add('EepromOxid', 'choice', array(
                    'required' => true,
                    'label' => 'EEPROM Oxid',
                    'choices' => $oxidChoices,
                    'data' => $memory['EepromOxid']
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'memory' => array(),
            'qualityChoices' => array(),
            'techChoices' => array(),
            'packageChoices' => array(),
            'eccChoices' => array(),
            'typeChoices' => array(),
        ));
    }

    public function getName() {
        return 'memoryForm';
    }
}