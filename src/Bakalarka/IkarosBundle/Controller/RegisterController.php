<?php

namespace Bakalarka\IkarosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Bakalarka\IkarosBundle\Entity\User;

class RegisterController extends Controller
{
    /**
     * @Route("/register", name="register")
     * @Template()
     */
    public function registerAction()
    {
         
         $user = new User();
         $form = $this->createFormBuilder($user)
      			->add('Username', 'text', array(        				
      				'required' => true, 
              'label' => 'Uživatelské jméno',
              'data' => '',
              'error_bubbling' => true,
      			))  
            ->add('Mail', 'email', array(        				
      				'required' => true, 
              'label' => 'E-mail',
              'data' => '',
              'error_bubbling' => true,
      			))      			
      			->add('Password', 'repeated', array(
              'type' => 'password',
              'invalid_message' => 'Hesla musí být stejná',             
              'required' => true,
              'first_options'  => array('label' => 'Heslo', 'error_bubbling' => true, ),
              'second_options' => array('label' => 'Heslo pro kontrolu', 'error_bubbling' => true, ),
              'data' => '',
                   
      			))
            
      			->getForm();
         
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') { 			
            $form->bind($request);
            if ($form->isValid()) {
                 try {
                    $em = $this->get('doctrine')->getManager();
                    
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);
                    $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                    $user->setPassword($password); 

                    $em->persist($user);
                    $em->flush();
                    
                } catch (\Exception $e) {
              		    return $this->redirect($this->generateUrl('register'));
              	}
                
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.context')->setToken($token);
                $this->get('session')->set('_security_main',serialize($token));       

                return $this->redirect($this->generateUrl('home'));
            }
        }
        
  
         
         return $this->render('BakalarkaIkarosBundle:Register:register.html.twig', array(
            'form' => $form->createView(),
          ));
         
          
    }
}
