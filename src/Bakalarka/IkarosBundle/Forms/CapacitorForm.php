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

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'qualityChoicesC' => array(),
            'matChoicesC' => array()
        ));
    }

    public function getName() {
        return 'capacitorForm';
    }
}