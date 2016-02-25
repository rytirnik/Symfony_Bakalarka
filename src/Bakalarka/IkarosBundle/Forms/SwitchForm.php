<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SwitchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)   {

        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];
        $swTypeChoices = $options['swTypeChoices'];

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
            ->add('SwitchType', 'choice', array(
                'required' => true,
                'label' => 'Popis',
                'choices' => $swTypeChoices
            ))
            ->add('Quality', 'choice', array(
                'required' => true,
                'label' => 'Kvalita',
                'choices' => array("MIL-SPEC" => "MIL-SPEC", "Lower" => "Lower")
            ))
            ->add('LoadType', 'choice', array(
                'required' => true,
                'label' => 'Typ zátěže',
                'error_bubbling' => true,
                'choices' => array("Resistive" => "Resistive", "Inductive" => "Inductive", "Lamp" => "Lamp")
            ))
            ->add('ContactCnt', 'integer', array(
                'required' => true,
                'label' => 'Počet kontaktů',
                'error_bubbling' => true,
                'attr' => array('min'=>0)
            ))
            ->add('OperatingCurrent', 'number', array(
                'required' => true,
                'label' => 'Pracovní proud [A]',
                'error_bubbling' => true
            ))
            ->add('RatedResistiveCurrent', 'integer', array(
                'required' => true,
                'label' => 'Maximální proud [A]',
                'error_bubbling' => true
            ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'swTypeChoices' => array()
        ));
    }

    public function getName() {
        return 'switchForm';
    }
}