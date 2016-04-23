<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InductiveForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];
        $qualityChoices = $options['qualityChoices'];
        $descChoices = $options['descChoices'];

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
                ->add('DevType', 'choice', array(
                    'required' => true,
                    'label' => 'Druh indukčnosti',
                    'choices' => array("Transformers" => "Transformers", "Coils" => "Coils"),
                    'data' => 'Transformers'
                ))
                ->add('Description', 'choice', array(
                    'required' => true,
                    'label' => 'Popis',
                    'choices' => $descChoices,
                    'data' => 'Worst case'
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => $qualityChoices,
                    'data' => 'Lower'
                ))
                ->add('PowerLoss', 'number', array(
                    'required' => true,
                    'label' => 'Ztrátový výkon [W]',
                    'error_bubbling' => true,
                    'data' => 0
                ))
                ->add('Weight', 'number', array(
                    'required' => false,
                    'label' => 'Hmotnost [kg]',
                    'error_bubbling' => true,
                ))
                ->add('Surface', 'number', array(
                    'required' => false,
                    'label' => 'Plocha [cm2]',
                    'error_bubbling' => true,
                ))
                ->add('TempDissipation', 'number', array(
                    'required' => true,
                    'label' => 'Oteplení ztrátovým výkonem [°C]',
                    'error_bubbling' => true,
                    'data' => 0
                ))
                ->add('TempPassive', 'number', array(
                    'required' => true,
                    'label' => 'Pasivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => 0
                ));


        }
        else {
            $inductive = $options['inductive'];

            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $inductive['Environment']
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $inductive['Label']
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $inductive['Type']
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $inductive['CasePart']
                ))
                ->add('DevType', 'choice', array(
                    'required' => true,
                    'label' => 'Druh indukčnosti',
                    'choices' => array("Transformers" => "Transformers", "Coils" => "Coils"),
                    'data' => $inductive['DevType']
                ))
                ->add('Description', 'choice', array(
                    'required' => true,
                    'label' => 'Popis',
                    'choices' => $descChoices,
                    'data' => $inductive['Description']
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => $qualityChoices,
                    'data' => $inductive['Quality']
                ))
                ->add('PowerLoss', 'number', array(
                    'required' => true,
                    'label' => 'Ztrátový výkon [W]',
                    'error_bubbling' => true,
                    'data' => $inductive['PowerLoss']
                ))
                ->add('Weight', 'number', array(
                    'required' => false,
                    'label' => 'Hmotnost [kg]',
                    'error_bubbling' => true,
                    'data' => $inductive['Weight']
                ))
                ->add('Surface', 'number', array(
                    'required' => false,
                    'label' => 'Plocha [cm2]',
                    'error_bubbling' => true,
                    'data' => $inductive['Surface']
                ))
                ->add('TempDissipation', 'number', array(
                    'required' => true,
                    'label' => 'Oteplení ztrátovým výkonem [°C]',
                    'error_bubbling' => true,
                    'data' => $inductive['TempDissipation']
                ))
                ->add('TempPassive', 'number', array(
                    'required' => true,
                    'label' => 'Pasivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => $inductive['TempPassive']
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'inductive' => array(),
            'qualityChoices' => array(),
            'descChoices' => array(),
        ));
    }

    public function getName() {
        return 'inductiveForm';
    }
}