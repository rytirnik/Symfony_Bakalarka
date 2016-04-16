<?php

namespace Bakalarka\IkarosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

use Bakalarka\IkarosBundle\Entity\User;
use Bakalarka\IkarosBundle\Entity\Resistor;
use Bakalarka\IkarosBundle\Entity\System;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Route("/home", name="home")
     * @Template()
     */
    public function indexAction()
    {
        /*$user = new User;
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword('admin', $user->getSalt());
        $user->setPassword($password);
        $user->setMail("admin@admin.cz");
        $user->setUsername("admin");

        $em = $this->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();*/


        return array();
    }

    /**
     * @Route("/norma", name="norma")
     * @Template()
     */
    public function normaAction()
    {
        return array();
    }
}
