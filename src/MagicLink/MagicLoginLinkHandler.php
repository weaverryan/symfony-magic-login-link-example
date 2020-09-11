<?php

namespace App\MagicLink;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MagicLoginLinkHandler
{
    private $secret;
    private $storage;
    private $urlGenerator;
    private $routeName;
    private $routeParams;

    public function __construct(AuthenticatableTokenDoctrineStorage $storage, UrlGeneratorInterface $urlGenerator, string $routeName, array $routeParams)
    {
        $this->secret = 'TODO';
        $this->storage = $storage;
        $this->urlGenerator = $urlGenerator;
        $this->routeName = $routeName;
        $this->routeParams = $routeParams;
    }

    // maybe allow route params here or the expiresAt
    public function createLoginUrl(object $user): string
    {
        $selector = $this->generateRandomString(15);
        $verifier = $this->generateRandomString(18);
        $hashedVerifier = \hash_hmac('sha256', $verifier, $this->secret);
        $expiresAt = new \DateTimeImmutable('+1 hour');

        $this->storage->storeToken($selector, $hashedVerifier, $user, $expiresAt);

        $params = $this->routeParams;
        $params['token'] = $selector.$verifier;
        $url = $this->urlGenerator->generate(
            $this->routeName,
            $params,
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        // TODO - maybe return an object with expiresAt
        return $url;
    }

    private function generateRandomString(int $bytes): string
    {
        return strtr(base64_encode(random_bytes($bytes)), '+/', '-_');
    }
}
