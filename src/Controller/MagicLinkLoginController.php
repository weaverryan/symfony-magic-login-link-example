<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MagicLinkLoginController extends AbstractController
{
    /**
     * @Route("/login")
     */
    public function requestMagicLink()
    {
        return $this->render('magic_link/login.html.twig');
    }
}
