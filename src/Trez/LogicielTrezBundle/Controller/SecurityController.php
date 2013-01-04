<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $request = $this->container->get('request');
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $this->get('session')->setFlash('error', "L'authentification a échoué !");
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
            $this->get('session')->setFlash('error', "L'authentification a échoué !");
        }

        return $this->render('TrezLogicielTrezBundle:Security:login_openid.html.twig', array());
    }

    public function loginCredentialsAction()
    {
        $request = $this->container->get('request');
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $this->get('session')->setFlash('error', "Nom d'utilisateur ou mot de passe invalide");
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
            $this->get('session')->setFlash('error', "Nom d'utilisateur ou mot de passe invalide");
        }

        return $this->render('TrezLogicielTrezBundle:Security:login_credentials.html.twig', array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME)
        ));
    }
}