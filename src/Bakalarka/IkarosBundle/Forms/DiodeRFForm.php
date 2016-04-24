<?php
/**
 * Created by PhpStorm.
 * User: Nikey
 * Date: 24.4.2016
 * Time: 15:28
 */

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DiodeRFForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
    $envChoices = $options['envChoices'];
    $sysEnv = $options['sysEnv'];
    $appChoices = $options['appChoices'];
    $qualityChoices = $options['qualityChoices'];
    $typeChoices = $options['typeChoices'];

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
                'data' => 'Worst case',
            ))
            ->add('DiodeType', 'choice', array(
                'required' => true,
                'label' => 'Typ diody',
                'choices' => $typeChoices,
                'data' => 'Worst case',
            ))
            ->add('Quality', 'choice', array(
                'required' => true,
                'label' => 'Kvalita',
                'choices' => $qualityChoices,
                'data' => "Worst case",
            ))
            ->add('PowerRated', 'number', array(
                'required' => false,
                'label' => 'Katalogový výkon [W]',
                'error_bubbling' => true,
            ))
            ->add('TempDissipation', 'number', array(
                'required' => true,
                'label' => 'Oteplení ztrát. výkonem [°C]',
                'error_bubbling' => true,
                'data' => 0
            ))
            ->add('TempPassive', 'number', array(
                'required' => true,
                'label' => 'Passivní oteplení [°C]',
                'error_bubbling' => true,
                'data' => 0
            ));
    }
    else {
        $diode = $options['diodeRF'];
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
            ->add('DiodeType', 'choice', array(
                'required' => true,
                'label' => 'Typ diody',
                'choices' => $typeChoices,
                'data' => $diode["DiodeType"]
            ))
            ->add('Quality', 'choice', array(
                'required' => true,
                'label' => 'Konstrukce kontaktu',
                'choices' => $qualityChoices,
                'data' => $diode["Quality"]
            ))
            ->add('PowerRated', 'number', array(
                'required' => false,
                'label' => 'Katalogový výkon [W]',
                'error_bubbling' => true,
                'data' => $diode['PowerRated']
            ))
            ->add('TempDissipation', 'number', array(
                'required' => true,
                'label' => 'Oteplení ztrát. výkonem [°C]',
                'error_bubbling' => true,
                'data' => $diode['TempDissipation']
            ))
            ->add('TempPassive', 'number', array(
                'required' => true,
                'label' => 'Passivní oteplení [°C]',
                'error_bubbling' => true,
                'data' => $diode['TempPassive']
            ));
    }
}

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'diodeRF' => array(),
            'qualityChoices' => array(),
            'typeChoices' => array(),
            'appChoices' => array(),

        ));
    }

    public function getName() {
        return 'diodeRFForm';
    }

}