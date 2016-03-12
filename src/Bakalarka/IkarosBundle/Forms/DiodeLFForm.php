<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DiodeLFForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];
        $appChoices = $options['appChoices'];
        $qualityChoices = $options['qualityChoices'];
        $contactChoices = $options['contactChoices'];

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
                ->add('Application', 'choice', array(
                    'required' => true,
                    'label' => 'Aplikace',
                    'choices' => $appChoices,
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => $qualityChoices,
                ))
                ->add('ContactConstruction', 'choice', array(
                    'required' => true,
                    'label' => 'Konstrukce kontaktu',
                    'choices' => $contactChoices,
                ))
                ->add('VoltageRated', 'number', array(
                    'required' => true,
                    'label' => 'Maximální napětí [V]',
                    'error_bubbling' => true,
                ))
                ->add('VoltageApplied', 'number', array(
                    'required' => true,
                    'label' => 'Provozní napětí [V]',
                    'error_bubbling' => true,
                ))
                ->add('DPTemp', 'number', array(
                    'required' => false,
                    'label' => 'Oteplení ztrát. výkonem [°C]',
                    'error_bubbling' => true,
                    'data' => 0
                ))
                ->add('PassiveTemp', 'number', array(
                    'required' => false,
                    'label' => 'Passivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => 0
                ));
        }
        else {
            $diode = $options['diodeLF'];
            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $diode["Environment"]
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $diode["Label"]
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $diode["Type"]
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $diode["CasePart"]
                ))
                ->add('Application', 'choice', array(
                    'required' => true,
                    'label' => 'Aplikace',
                    'choices' => $appChoices,
                    'data' => $diode["Application"]
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => $qualityChoices,
                    'data' => $diode["Quality"]
                ))
                ->add('ContactConstruction', 'choice', array(
                    'required' => true,
                    'label' => 'Konstrukce kontaktu',
                    'choices' => $contactChoices,
                    'data' => $diode["ContactConstruction"]
                ))
                ->add('VoltageRated', 'number', array(
                    'required' => true,
                    'label' => 'Maximální napětí [V]',
                    'error_bubbling' => true,
                    'data' => $diode["VoltageRated"]
                ))
                ->add('VoltageApplied', 'number', array(
                    'required' => true,
                    'label' => 'Provozní napětí [V]',
                    'error_bubbling' => true,
                    'data' => $diode["VoltageApplied"]
                ))
                ->add('DPTemp', 'number', array(
                    'required' => false,
                    'label' => 'Oteplení ztrát. výkonem [°C]',
                    'error_bubbling' => true,
                    'data' => $diode["DPTemp"]
                ))
                ->add('PassiveTemp', 'number', array(
                    'required' => false,
                    'label' => 'Passivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => $diode["PassiveTemp"]
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'diodeLF' => array(),
            'qualityChoices' => array(),
            'contactChoices' => array(),
            'appChoices' => array()

    ));
    }

    public function getName() {
        return 'diodeLFForm';
    }
}