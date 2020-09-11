<?php

namespace App\MagicLink;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\MagicLink\MagicLoginLinkHelper;

class MagicLinkAuthenticator extends AbstractAuthenticator
{
    private $magicLoginLinkHelper;
    private $httpUtils;
    private $options;

    public function __construct(MagicLoginLinkHelper $magicLoginLinkHelper, HttpUtils $httpUtils)
    {
        $this->magicLoginLinkHelper = $magicLoginLinkHelper;
        $this->httpUtils = $httpUtils;

        $this->options = [
            'login_path' => 'magic_link_login',
            'check_path' => 'magic_link_verify',
        ];
    }

    public function supports(Request $request): ?bool
    {
        return $this->httpUtils->checkRequestPath($request, $this->options['check_path']);
    }

    public function authenticate(Request $request): PassportInterface
    {
        $token = $request->get('token');

        return new SelfValidatingPassport(
            new UserBadge($token, function($token) {
                $user = $this->magicLoginLinkHelper
                    ->consumeToken($token);

                if (!$user) {
                    // todo - real exception class
                    throw new CustomUserMessageAuthenticationException('Invalid magic URL');
                }

                return $user;
            }),
            []
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->httpUtils->generateUri($request, 'app_homepage'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->httpUtils->generateUri($request, $this->options['login_path']);
    }

    /**
     * Override to change what happens after a bad username/password is submitted.
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        // TODO failure handler?
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }
}
