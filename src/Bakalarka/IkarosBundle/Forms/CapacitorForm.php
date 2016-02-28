<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CapacitorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)   {

        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];
        $QualityChoicesC = $options['qualityChoicesC'];
        $MatChoicesC = $options['matChoicesC'];

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
                ));
        }

        else {
            $capacitor = $options['capacitor'];
            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $capacitor["Environment"]
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $capacitor["Label"]
                ))
                ->add('Quality', 'choice', array(
                    'label' => 'Kvalita',
                    'choices' => $QualityChoicesC,
                    'required' => true,
                    'data' => $capacitor["Quality"]
                ))
                ->add('Material', 'choice', array(
                    'label' => 'Materiál',
                    'choices' => $MatChoicesC,
                    'required' => true,
                    'data' => $capacitor["Material"]
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $capacitor["Type"]
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $capacitor["CasePart"]
                ))
                ->add('Value', 'integer', array(
                    'required' => true,
                    'label' => 'Hodnota [μF]',
                    'error_bubbling' => true,
                    'data' => $capacitor["Value"]
                ))
                ->add('VoltageMax', 'number', array(
                    'required' => true,
                    'label' => 'Maximální napětí [V]',
                    'error_bubbling' => true,
                    'data' => $capacitor["VoltageMax"]
                ))
                ->add('VoltageOperational', 'number', array(
                    'required' => true,
                    'label' => 'Provozní napětí [V]',
                    'error_bubbling' => true,
                    'data' => $capacitor["VoltageOperational"]
                ))
                ->add('VoltageDC', 'number', array(
                    'required' => false,
                    'label' => 'Napětí DC [V]',
                    'error_bubbling' => true,
                    'data' => $capacitor["VoltageDC"]
                ))
                ->add('VoltageAC', 'number', array(
                    'required' => false,
                    'label' => 'Napětí AC [V]',
                    'error_bubbling' => true,
                    'data' => $capacitor["VoltageAC"]
                ))
                ->add('SerialResistor', 'number', array(
                    'required' => false,
                    'label' => 'Odpor v sérii tantaly [Ω]',
                    'error_bubbling' => true,
                    'data' => $capacitor["SerialResistor"]
                ))
                ->add('PassiveTemp', 'integer', array(
                    'required' => true,
                    'label' => 'Pasivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => $capacitor["PassiveTemp"]
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'qualityChoicesC' => array(),
            'matChoicesC' => array(),
            'capacitor' => array()
        ));
    }

    public function getName() {
        return 'capacitorForm';
    }
}