<?php

namespace Bakalarka\IkarosBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SystemForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $envChoices = $options['envChoices'];
        $mode = $options['mode'];

        if($mode == "newSystem") {
            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                ))
                ->add('Title', 'text', array(
                    'required' => true,
                    'label' => 'Název systému',
                    'data' => '',
                    'error_bubbling' => true,
                    'max_length' => 30,
                ))
                ->add('Temp', 'integer', array(
                    'required' => true,
                    'label' => 'Teplota',
                    'error_bubbling' => true,
                ))
                ->add('Note', 'textarea', array(
                    'required' => false,
                    'label' => 'Poznámka (max 500 znaků)',
                    'error_bubbling' => true,
                    'max_length' => 500
                ));
        }
        else {
            $system = $options['system'];
            $builder
                ->add('Environment', 'choice', array(
                    'label' => 'Prostředí',
                    'choices' => $envChoices,
                    'required' => true,
                    'data' => $system["Environment"]
                ))
                ->add('Title', 'text', array(
                    'required' => true,
                    'label' => 'Název systému',
                    'data' => '',
                    'error_bubbling' => true,
                    'max_length' => 30,
                    'data' => $system["Title"]
                ))
                ->add('Temp', 'integer', array(
                    'required' => true,
                    'label' => 'Teplota',
                    'error_bubbling' => true,
                    'data' => $system["Temp"]
                ))
                ->add('Note', 'textarea', array(
                    'required' => false,
                    'label' => 'Poznámka (max 500 znaků)',
                    'error_bubbling' => true,
                    'max_length' => 500,
                    'data' => $system["Note"]
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Bakalarka\IkarosBundle\Entity\System',
            'envChoices' => array(),
            'mode' => "newSystem",
            'system' => array()
        ));
    }

    public function getName() {
        return 'sysForm';
    }
}