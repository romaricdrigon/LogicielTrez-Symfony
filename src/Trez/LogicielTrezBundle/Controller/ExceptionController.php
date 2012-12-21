<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController as OriginalExceptionController;
/**
 * Based on Symfony2 ExceptionController,
 * Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction
 */
class ExceptionController extends OriginalExceptionController
{
    public function showAction(FlattenException $exception, DebugLoggerInterface $logger = null, $format = 'html')
    {
        if ('locked exercice' === $exception->getMessage()) {
            $this->container->get('session')->setFlash('error', "Vous ne pouvez pas éditer un exercice verrouillé ou ses fils");

            header('Location: '.$this->container->get('request')->getRequestUri());
            exit();
        }
        if ('locked budget' === $exception->getMessage()) {
            $this->container->get('session')->setFlash('error', "Vous ne pouvez pas éditer un budget verrouillé ou ses fils");

            header('Location: '.$this->container->get('request')->getRequestUri());
            exit();
        }

        if ('Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException' === $exception->getClass()) {
            $this->container->get('session')->setFlash('error', "Vous n'avez pas les privilèges nécessaires pour effectuer cette action !");

            $sc = $this->container->get('security.context');

            if ($sc->isGranted('ROLE_USER') === true) {
                return new Response($this->container->get('templating')->render(
                    'TrezLogicielTrezBundle:Default:index.html.twig',
                    array()
                ));
            } else {
                return new Response("Vous n'avez pas les privilèges nécessaires pour afficher cette page !");
            }
        }

        // else default behavior
        return parent::showAction($exception, $logger, $format);
    }
}