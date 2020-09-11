<?php

namespace App\MagicLink;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MagicLoginLinkHandler
{
    private $urlGenerator;
    private $routeName;
    private $routeParams;

    public function __construct(UrlGeneratorInterface $urlGenerator, string $routeName, array $routeParams)
    {
        $this->urlGenerator = $urlGenerator;
        $this->routeName = $routeName;
        $this->routeParams = $routeParams;
    }

    public function createLink(object $user)
    {
        $selector = $this->generateRandomString(15);
        $verifier = $this->generateRandomString(18);

    }

    private function generateRandomString(int $bytes): string
    {
        return strtr(base64_encode(random_bytes(32)), '+/', '-_');
    }
}
