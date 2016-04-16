<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OptoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];
        $appChoices = $options['appChoices'];
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
                ->add('Application', 'choice', array(
                    'required' => true,
                    'label' => 'Aplikace',
                    'choices' => $appChoices,
                    'data' => 'Worst case'
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => $qualityChoices,
                    'data' => 'Worst case'
                ))
                ->add('DPTemp', 'number', array(
                    'required' => true,
                    'label' => 'Oteplení ztrát. výkonem [°C]',
                    'error_bubbling' => true,
                    'data' => 0
                ))
                ->add('PassiveTemp', 'number', array(
                    'required' => true,
                    'label' => 'Passivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => 0
                ));
        }
        else {
            $opto = $options['opto'];
            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $opto["Environment"]
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $opto["Label"]
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $opto["Type"]
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $opto["CasePart"]
                ))
                ->add('Application', 'choice', array(
                    'required' => true,
                    'label' => 'Aplikace',
                    'choices' => $appChoices,
                    'data' => $opto["Application"]
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => $qualityChoices,
                    'data' => $opto["Quality"]
                ))
                ->add('DPTemp', 'number', array(
                    'required' => true,
                    'label' => 'Oteplení ztrát. výkonem [°C]',
                    'error_bubbling' => true,
                    'data' => $opto["DPTemp"]
                ))
                ->add('PassiveTemp', 'number', array(
                    'required' => true,
                    'label' => 'Passivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => $opto["PassiveTemp"]
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'opto' => array(),
            'qualityChoices' => array(),
            'appChoices' => array()

    ));
    }

    public function getName() {
        return 'optoForm';
    }
}