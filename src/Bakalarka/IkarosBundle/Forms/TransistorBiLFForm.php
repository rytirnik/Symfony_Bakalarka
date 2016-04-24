<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TransistorBiLFForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];
        $qualityChoices = $options['qualityChoices'];

        $appChoices = array("Linear Amplification" => "Linear Amplification", "Switching" => "Switching", "Worstcase" => "Worst case");

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
                    'data' => 'Worstcase'
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => $qualityChoices,
                    'data' => 'Worst case'
                ))
                ->add('PowerRated', 'number', array(
                    'required' => true,
                    'label' => 'Jmenovitý výkon [W]',
                    'error_bubbling' => true,
                    'data' => 0
                ))
                ->add('VoltageCE', 'number', array(
                    'required' => true,
                    'label' => 'Napětí CE [V]',
                    'error_bubbling' => true,
                    'data' => 0
                ))
                ->add('VoltageCEO', 'number', array(
                    'required' => true,
                    'label' => 'Napětí CEO [V]',
                    'error_bubbling' => true,
                    'data' => 0
                ))
                ->add('TempDissipation', 'number', array(
                    'required' => true,
                    'label' => 'Oteplení ztrát. výkonem [°C]',
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
            $transistor = $options['transistor'];
            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $transistor['Environment']
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $transistor['Label']
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $transistor['Type']
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $transistor['CasePart']
                ))
                ->add('Application', 'choice', array(
                    'required' => true,
                    'label' => 'Aplikace',
                    'choices' => $appChoices,
                    'data' => $transistor['Application']
                ))
                ->add('Quality', 'choice', array(
                    'required' => true,
                    'label' => 'Kvalita',
                    'choices' => $qualityChoices,
                    'data' => $transistor['Quality']
                ))
                ->add('PowerRated', 'number', array(
                    'required' => true,
                    'label' => 'Jmenovitý výkon [W]',
                    'error_bubbling' => true,
                    'data' => $transistor['PowerRated']
                ))
                ->add('VoltageCE', 'number', array(
                    'required' => true,
                    'label' => 'Napětí CE [V]',
                    'error_bubbling' => true,
                    'data' => $transistor['VoltageCE']
                ))
                ->add('VoltageCEO', 'number', array(
                    'required' => true,
                    'label' => 'Napětí CEO [V]',
                    'error_bubbling' => true,
                    'data' => $transistor['VoltageCEO']
                ))
                ->add('TempDissipation', 'number', array(
                    'required' => true,
                    'label' => 'Oteplení ztrát. výkonem [°C]',
                    'error_bubbling' => true,
                    'data' => $transistor['TempDissipation']
                ))
                ->add('TempPassive', 'number', array(
                    'required' => true,
                    'label' => 'Pasivní oteplení [°C]',
                    'error_bubbling' => true,
                    'data' => $transistor['TempPassive']
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'transistor' => array(),
            'qualityChoices' => array(),
        ));
    }

    public function getName() {
        return 'transistorBiLFForm';
    }
}