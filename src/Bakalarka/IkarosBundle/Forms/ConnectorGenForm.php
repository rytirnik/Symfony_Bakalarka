<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConnectorGenForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)   {

        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];
        $conGenTypeChoices = $options['conGenTypeChoices'];

        if($sysEnv)
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
                    'choices' => $conGenTypeChoices
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
                ->add('ContactCnt', 'integer', array(
                    'required' => true,
                    'label' => 'Počet kontaktů',
                    'error_bubbling' => true,
                    'attr' => array('min'=>0)
                ))
                ->add('CurrentContact', 'number', array(
                    'required' => true,
                    'label' => 'Proud na kontakt [A]',
                    'error_bubbling' => true
                ))
                ->add('MatingFactor', 'integer', array(
                    'required' => true,
                    'label' => 'Počet spoj/rozp za 1000h',
                    'error_bubbling' => true
                ))
                ->add('PassiveTemp', 'integer', array(
                    'required' => true,
                    'label' => 'Pasivní oteplení  [°C]',
                    'error_bubbling' => true
                ));
        else {
            $connectorGen = $options['connectorGen'];
            $builder
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $connectorGen["Environment"]
            ))
                ->add('ConnectorType', 'choice', array(
                    'required' => true,
                    'label' => 'Popis',
                    'choices' => $conGenTypeChoices,
                    'data' => $connectorGen["ConnectorType"]
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower"),
                    'data' => $connectorGen["Quality"]
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $connectorGen["Label"]
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $connectorGen["Type"]
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $connectorGen["CasePart"]
                ))
                ->add('ContactCnt', 'integer', array(
                    'required' => true,
                    'label' => 'Počet kontaktů',
                    'error_bubbling' => true,
                    'attr' => array('min'=>0),
                    'data' => $connectorGen["ContactCnt"]
                ))
                ->add('CurrentContact', 'number', array(
                    'required' => true,
                    'label' => 'Proud na kontakt [A]',
                    'error_bubbling' => true,
                    'data' => $connectorGen["CurrentContact"]
                ))
                ->add('MatingFactor', 'integer', array(
                    'required' => true,
                    'label' => 'Počet spoj/rozp za 1000h',
                    'error_bubbling' => true,
                    'data' => $connectorGen["MatingFactor"]
                ))
                ->add('PassiveTemp', 'integer', array(
                    'required' => true,
                    'label' => 'Pasivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => $connectorGen["PassiveTemp"]
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'conGenTypeChoices' => array(),
            'connectorGen' => array()
        ));
    }

    public function getName() {
        return 'connectorGenForm';
    }
}