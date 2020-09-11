<?php

namespace App\Controller;

use App\MagicLink\MagicLoginLinkHandler;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MagicLinkLoginController extends AbstractController
{
    /**
     * @Route("/login", name="magic_link_login")
     */
    public function requestMagicLink(Request $request, MagicLoginLinkHandler $magicLoginLinkHandler, UserRepository $userRepository, AuthenticationUtils $authenticationUtils)
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            $user = $userRepository->findOneBy(['email' => $email]);

            // todo - timing attack here
            if ($user) {
                $url = $magicLoginLinkHandler->createLoginUrl($user);

                dump($url);
            }

            return $this->redirectToRoute('magic_link_check_email');
        }

        return $this->render('magic_link/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
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
