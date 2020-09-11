<?php

namespace App\MagicLink;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MagicLoginLinkHandler
{
    private const SELECTOR_LENGTH = 20;

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
        // length will be 20
        $selector = $this->generateRandomString(15);
        $verifier = $this->generateRandomString(18);
        $expiresAt = new \DateTimeImmutable('+1 hour');

        $authenticatableToken = new AuthenticatableToken(
            $selector,
            $this->hashVerifier($verifier),
            $user,
            $expiresAt
        );

        $this->storage->storeToken($authenticatableToken);

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

    public function consumeToken(string $token): ?object
    {
        $selector = \substr($token, 0, self::SELECTOR_LENGTH);
        $verifier = \substr($token, self::SELECTOR_LENGTH);
        $hashedVerifier = $this->hashVerifier($verifier);

        $token = $this->storage->findToken($selector);
        // immediately invalidate, even if the verifier is wrong
        $this->storage->invalidateToken($selector);

        $storedVerifier = $token ? $token->getHashedVerifier() : 'fake_verifier';

        if (false === \hash_equals($hashedVerifier, $storedVerifier)) {
            // todo - maybe throw a specific exception
            return null;
        }

        if ($token->isExpired()) {
            // todo - maybe throw a specific exception
            return null;
        }

        return $token->getUser();
    }

    private function hashVerifier(string $verifier): string
    {
        return \hash_hmac('sha256', $verifier, $this->secret);
    }
}
