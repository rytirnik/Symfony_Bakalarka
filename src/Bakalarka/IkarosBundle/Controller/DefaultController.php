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
     * @Route("/home")
     * @Template()
     */
    public function indexAction()
    {
          /*try {
              $em = $this->get('doctrine')->getManager();
              $admin = new User;

              $factory = $this->get('security.encoder_factory');
              $encoder = $factory->getEncoder($admin);
              $password = $encoder->encodePassword('adminpass', $admin->getSalt());
              $admin->setPassword($password);
              $admin->setUsername("admin");
              $admin->setRole('ROLE_ADMIN');
              $admin->setMail("admin@admin.cz");

              $system = new System();
              $system->setTitle("Systemek Admina");
              $system->setTemp(20);
              $em->persist($admin);
              $em->persist($system);

              //$admin->addSystem($system);
              $system->setUserID($admin);
              $em->persist($admin);
              $em->persist($system);

              $em->flush();
          } catch (\Exception $e) {
        		$result['e'] = $e;
        	}*/

        /*try {
            $em = $this->get('doctrine')->getManager();

            $user = new User;

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword('userpass', $user->getSalt());
            $user->setPassword($password);
            $user->setUsername("user");
            $user->setMail("user@user.cz");

            $system = new System();
            $system->setTitle("Systemek Usera");
            $system->setTemp(50);

            $em->persist($user);
            $em->persist($system);

            //$user->addSystem($system);
            $system->setUserID($user);

            $em->persist($user);
            $em->persist($system);

            $em->flush();
        } catch (\Exception $e) {
            $result['e'] = $e;
        }*/

        
        return array();
    }

    /**
     * @Route("/norma")
     * @Template()
     */
    public function normaAction()
    {

        return array();
    }
}
