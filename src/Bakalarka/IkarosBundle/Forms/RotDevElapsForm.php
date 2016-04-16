<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RotDevElapsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)   {

        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];

        if($sysEnv) {
            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $sysEnv
                ))
                ->add('DevType', 'choice', array(
                    'required' => true,
                    'label' => 'Popis',
                    'choices' => array("A.C." => "A.C.", "Inverter Driven" => "Inverter Driven",
                        "Commutator D.C." => "Commutator D.C.", "Worstcase" => "Worst case"),
                    'data' => 'Worstcase',
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
                ));
        }
        else {
            $rotElaps = $options['rotElaps'];
            $builder
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $rotElaps["Environment"]
            ))
                ->add('DevType', 'choice', array(
                    'required' => true,
                    'label' => 'Popis',
                    'choices' => array("A.C." => "A.C.", "Inverter Driven" => "Inverter Driven",
                        "Commutator D.C." => "Commutator D.C.", "Worstcase" => "Worst case"),
                    'data' => $rotElaps["DevType"]
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $rotElaps["Label"]
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $rotElaps["Type"]
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $rotElaps["CasePart"]
                ))
                ->add('TempOperational', 'integer', array(
                    'required' => true,
                    'label' => 'Provozní teplota [°C]',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $rotElaps["TempOperational"]
                ))
                ->add('TempMax', 'integer', array(
                    'required' => true,
                    'label' => 'Maximální teplota [°C]',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $rotElaps["TempMax"]
                ));
        }

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'rotElaps' => array()
        ));
    }

    public function getName() {
        return 'rotDevElapsForm';
    }
}