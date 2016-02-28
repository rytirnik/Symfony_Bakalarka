<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConnectorSocForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)   {

        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];
        $conSocTypeChoices = $options['conSocTypeChoices'];

        if($sysEnv) {
            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $sysEnv
                ))
                ->add('ConnectorType', 'choice', array(
                    'required' => true,
                    'label' => 'Popis',
                    'choices' => $conSocTypeChoices
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
                ))
                ->add('ActivePins', 'integer', array(
                    'required' => true,
                    'label' => 'Aktivní piny',
                    'error_bubbling' => true,
                    'attr' => array('min'=>1)
                ));
        }
        else {
            $connectorSoc = $options['connectorSoc'];
            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $connectorSoc["Environment"]
                ))
                ->add('ConnectorType', 'choice', array(
                    'required' => true,
                    'label' => 'Popis',
                    'choices' => $conSocTypeChoices,
                    'data' => $connectorSoc["ConnectorType"]
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower"),
                    'data' => $connectorSoc["Quality"]
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $connectorSoc["Label"]
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $connectorSoc["Type"]
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $connectorSoc["CasePart"]
                ))
                ->add('ActivePins', 'integer', array(
                    'required' => true,
                    'label' => 'Aktivní piny',
                    'error_bubbling' => true,
                    'attr' => array('min'=>1),
                    'data' => $connectorSoc["ActivePins"]
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'conSocTypeChoices' => array(),
            'connectorSoc' => array()
        ));
    }

    public function getName() {
        return 'connectorSocForm';
    }
}