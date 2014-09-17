<?php

namespace Bakalarka\IkarosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

class LoginController extends Controller
{
    /**
     * @Route("/login")
     * @Template()
     */
    public function loginAction()
    {
       $request = $this->getRequest();
          $session = $request->getSession();
 
          // načtení případné chyby z minulého přihlášení
          if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
              $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
          } else {
              $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
          }
   
          // šablona s formulářem
          return $this->render('BakalarkaIkarosBundle:Login:login.html.twig', array(
              // tímto způsobem získáme naposledy zadané uživatelské jméno
              'last_username' => $session->get(SecurityContext::LAST_USERNAME),
              // do šablony předáme i případnou zjištěnou chybu
              'error' => $error,
              
          ));
       
    }
}
