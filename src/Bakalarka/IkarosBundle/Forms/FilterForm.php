<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)   {

        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];
        $filterTypeChoices = $options['filterTypeChoices'];

        if($sysEnv)
        $builder
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $sysEnv
            ))
            ->add('FilterType', 'choice', array(
                'required' => true,
                'label' => 'Popis',
                'choices' => $filterTypeChoices
            ))
            ->add('Quality', 'choice', array(
                'required' => true,
                'label' => 'Kvalita',
                'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower")
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
            ));
        else {
            $filter = $options['filter'];
            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $filter["Environment"]
                ))
                ->add('FilterType', 'choice', array(
                    'required' => true,
                    'label' => 'Popis',
                    'choices' => $filterTypeChoices,
                    'data' => $filter["FilterType"]
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower"),
                    'data' => $filter["Quality"]
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $filter["Label"]
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $filter["Type"]
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $filter["CasePart"]
                ));
        }

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'filterTypeChoices' => array(),
            'filter' => array()
        ));
    }

    public function getName() {
        return 'filterForm';
    }
}