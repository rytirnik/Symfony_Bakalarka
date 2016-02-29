<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResistorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)   {

        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];
        $QualityChoices = $options['qualityChoices'];
        $MatChoices = $options['matChoices'];

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
                ));
        }
        else {
            $res = $options['resistor'];
            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $res["Environment"]
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $res["Label"]
                ))
                ->add('Quality', 'choice', array(
                    'label' => 'Kvalita',
                    'choices' => $QualityChoices,
                    'required' => true,
                    'data' => $res["Quality"]
                ))
                ->add('Material', 'choice', array(
                    'label' => 'Materiál',
                    'choices' => $MatChoices,
                    'required' => true,
                    'data' => $res["Material"]
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $res["Type"]
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $res["CasePart"]
                ))
                ->add('Value', 'integer', array(
                    'required' => false,
                    'label' => 'Hodnota [Ω]',
                    'error_bubbling' => true,
                    'data' => $res["Value"]
                ))
                ->add('MaxPower', 'number', array(
                    'required' => true,
                    'label' => 'Maximální výkon [W]',
                    'error_bubbling' => true,
                    'data' => $res["MaxPower"]
                ))
                ->add('VoltageOperational', 'number', array(
                    'required' => false,
                    'label' => 'Provozní napětí [V]',
                    'error_bubbling' => true,
                    'data' => $res["VoltageOperational"]
                ))
                ->add('CurrentOperational', 'number', array(
                    'required' => false,
                    'label' => 'Provozní proud [A]',
                    'error_bubbling' => true,
                    'data' => $res["CurrentOperational"]
                ))
                ->add('DissipationPower', 'number', array(
                    'required' => true,
                    'label' => 'Ztrátový výkon [W]',
                    'error_bubbling' => true,
                    'data' => $res["DissipationPower"]
                ))
                ->add('DPTemp', 'number', array(
                    'required' => true,
                    'label' => 'Oteplení ztrát. výkonem [°C]',
                    'error_bubbling' => true,
                    'data' => $res["DPTemp"]
                ))
                ->add('PassiveTemp', 'number', array(
                    'required' => true,
                    'label' => 'Pasivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => $res["PassiveTemp"]
                ))
                ->add('Alternate', 'number', array(
                    'required' => false,
                    'label' => 'Střídavý proud [A]',
                    'error_bubbling' => true,
                    'data' => $res["Alternate"]
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'qualityChoices' => array(),
            'matChoices' => array(),
            'resistor' => array()
        ));
    }

    public function getName() {
        return 'resistorForm';
    }
}