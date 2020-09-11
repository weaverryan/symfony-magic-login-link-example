<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MagicLinkLoginController extends AbstractController
{
    /**
     * @Route("/login")
     */
    public function requestMagicLink(Request $request)
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

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
}
