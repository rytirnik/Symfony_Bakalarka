<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TubeWaveForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)   {

        $envChoices = $options['envChoices'];
        $sysEnv = $options['sysEnv'];

        if($sysEnv)
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
                ->add('Power', 'integer', array(
                    'required' => true,
                    'label' => 'Výkon (10-40000) [W]',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'attr' => array('min'=>10, 'max'=>40000)
                ))
                ->add('Frequency', 'number', array(
                    'required' => true,
                    'label' => 'Frekvence (0.1-18) [GHz]',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'attr' => array('min'=>0.1, 'max'=>18)
                ));
        else {
            $tubeWave = $options['tubeWave'];
            $builder
            ->add('Environment', 'choice', array(
                'label' => 'Prostředí',
                'choices' => $envChoices,
                'required' => true,
                'data' => $tubeWave["Environment"]
            ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $tubeWave["Label"]
                ))
                ->add('Type', 'text', array(
                    'required' => false,
                    'label' => 'Typ',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $tubeWave["Type"]
                ))
                ->add('CasePart', 'text', array(
                    'required' => false,
                    'label' => 'Pouzdro',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'data' => $tubeWave["CasePart"]
                ))
                ->add('Power', 'integer', array(
                    'required' => true,
                    'label' => 'Výkon (10-40000) [W]',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'attr' => array('min'=>10, 'max'=>40000),
                    'data' => $tubeWave["Power"]
                ))
                ->add('Frequency', 'number', array(
                    'required' => true,
                    'label' => 'Frekvence (0.1-18) [GHz]',
                    'error_bubbling' => true,
                    'max_length' => 64,
                    'attr' => array('min'=>0.1, 'max'=>18),
                    'data' => $tubeWave["Frequency"]
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'envChoices' => array(),
            'sysEnv' => "GB",
            'tubeWave' => array()
        ));
    }

    public function getName() {
        return 'tubeWaveForm';
    }
}