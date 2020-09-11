<?php

namespace App\Controller;

use App\MagicLink\MagicLoginLinkHandler;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MagicLinkLoginController extends AbstractController
{
    /**
     * @Route("/login")
     */
    public function requestMagicLink(Request $request, MagicLoginLinkHandler $magicLoginLinkHandler)
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            $magicLoginLinkHandler->createLink(new \stdClass());

            return $this->redirectToRoute('magic_link_check_email');
        }

        return $this->render('magic_link/login.html.twig');
    }

    /**
     * @Route("/login/check-email", name="magic_link_check_email")
     */
    public function magicLinkCheckEmail()
    {
        return $this->render('magic_link/check_email.html.twig');
    }

    /**
     * @Route("/login/verify", name="magic_link_verify")
     */
    public function checkMagicLink()
    {
        throw new \Exception('will be handled by authenticator');
    }
}
