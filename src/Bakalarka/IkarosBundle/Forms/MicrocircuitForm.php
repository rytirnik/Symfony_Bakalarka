<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MicrocircuitForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];

        $descChoices = array("Bipolar" => "Bipolar", "MOS" => "MOS");
        $appChoices = array("Digital" => "Digital", "Linear" => "Linear", "PLA/PAL" => "PLA/PAL");
        $packageChoices = $options['packageChoices'];
        $techChoices = $options['techChoices'];
        $qualityChoices = $options['qualityChoices'];

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
                ))
                ->add('Application', 'choice', array(
                    'required' => true,
                    'label' => 'Aplikace',
                    'choices' => $appChoices,

                ))
                ->add('GateCount', 'number', array(
                    'required' => true,
                    'label' => 'Počet hradel',
                    'data' => 1
                ))
                ->add('Technology', 'choice', array(
                    'required' => true,
                    'label' => 'Technologie',
                    'choices' => $techChoices,
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
                ));
        }
        else {
            $micro = $options['microcircuit'];

            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $micro['Environment']
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $micro['Label']
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $micro['Type']
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $micro['CasePart']
                ))
                ->add('Description', 'choice', array(
                    'required' => true,
                    'label' => 'Popis',
                    'choices' => $descChoices,
                    'data' => $micro['Description']
                ))
                ->add('Application', 'choice', array(
                    'required' => true,
                    'label' => 'Aplikace',
                    'choices' => $appChoices,
                    'data' => $micro['Application']
                ))
                ->add('GateCount', 'number', array(
                    'required' => true,
                    'label' => 'Počet hradel',
                    'data' => $micro['GateCount']
                ))
                ->add('Technology', 'choice', array(
                    'required' => true,
                    'label' => 'Technologie',
                    'choices' => $techChoices,
                    'data' => $micro['Technology']
                ))
                ->add('PackageType', 'choice', array(
                    'required' => true,
                    'label' => 'Provedení pouzdra',
                    'choices' => $packageChoices,
                    'data' => $micro['PackageType']
                ))
                ->add('PinCount', 'number', array(
                    'required' => true,
                    'label' => 'Počet vývodů',
                    'error_bubbling' => true,
                    'data' => $micro['PinCount']
                ))
                ->add('ProductionYears', 'number', array(
                    'required' => true,
                    'label' => 'Doba výroby [roky]',
                    'error_bubbling' => true,
                    'data' => $micro['ProductionYears']
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => $qualityChoices,
                    'data' => $micro['Quality']
                ))
                ->add('TempDissipation', 'number', array(
                    'required' => true,
                    'label' => 'Oteplení ztrát. výkonem [°C]',
                    'error_bubbling' => true,
                    'data' => $micro['TempDissipation']
                ))
                ->add('TempPassive', 'number', array(
                    'required' => true,
                    'label' => 'Pasivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => $micro['TempPassive']
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'microcircuit' => array(),
            'qualityChoices' => array(),
            'techChoices' => array(),
            'packageChoices' => array(),
        ));
    }

    public function getName() {
        return 'microcircuitForm';
    }
}