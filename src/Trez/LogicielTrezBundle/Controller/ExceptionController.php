<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController as OriginalExceptionController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Based on Symfony2 ExceptionController,
 * Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction
 */
class ExceptionController extends OriginalExceptionController
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    protected $securityContext;

    public function __construct(\Twig_Environment $twig, $debug, Session $session, Request $request, SecurityContext $sc)
    {
        parent::__construct($twig, $debug);

        $this->session = $session;
        $this->request = $request;
        $this->securityContext = $sc;
    }

    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null, $format = 'html')
    {
        if ('Trez\LogicielTrezBundle\Exception\LockedException' === $exception->getClass()) {
            $this->session->getFlashBag()->set('error', "Vous ne pouvez pas éditer un exercice/budget verrouillé ou ses fils");

            return new RedirectResponse($this->request->getRequestUri(), 302);
        }

        if ('Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException' === $exception->getClass()) {
            $this->session->getFlashBag()->set('error', "Vous n'avez pas les privilèges nécessaires pour effectuer cette action !");

            if ($this->securityContext->isGranted('ROLE_USER') === true) {
                return new Response($this->twig->render(
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
