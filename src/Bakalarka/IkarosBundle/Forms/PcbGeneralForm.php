<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PcbGeneralForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)   {

        $EquipChoices = $options['EquipChoices'];
        $MatChoices = $options['MatChoices'];
        $sysID = $options['sysID'];

        if($sysID == -1) {
            $builder
                ->add('system', 'choice', array(
                    'mapped' => false,
                    'label' => 'Systém',
                    'choices' => $sysID,
                    'required' => true,
                ))
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název desky',
                    'data' => '',
                    'error_bubbling' => true,
                    'max_length' => 30,
                ))
                ->add('Lifetime', 'integer', array(
                    'label' => 'Životnost',
                    'required' => true,
                    'error_bubbling' => true,
                    'attr' => array('min' => 1)
                ))
                ->add('EquipType', 'choice', array(
                    'label' => 'Aplikace v odvětví',
                    'choices' => $EquipChoices,
                    'required' => true,
                ))
                ->add('SubstrateMaterial', 'choice', array(
                    'label' => 'Materiál',
                    'choices' => $MatChoices,
                    'required' => true,
                ))


                ->getForm();
        }
        else {
            $builder
                ->add('Label', 'text', array(
                    'required' => true,
                    'label' => 'Název desky',
                    'data' => '',
                    'error_bubbling' => true,
                    'max_length' => 30,
                ))
                ->add('Lifetime', 'integer', array(
                    'label' => 'Životnost (roky)',
                    'required' => true,
                    'error_bubbling' => true,
                    'attr' => array('min' => 1)
                ))
                ->add('EquipType', 'choice', array(
                    'label' => 'Aplikace v odvětví',
                    'choices' => $EquipChoices,
                    'required' => true,
                ))
                ->add('SubstrateMaterial', 'choice', array(
                    'label' => 'Materiál',
                    'choices' => $MatChoices,
                    'required' => true,
                ));
        }

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'EquipChoices' => array(),
            'MatChoices' => array(),
            'sysID' => -1
        ));
    }

    public function getName() {
        return 'form';
    }
}