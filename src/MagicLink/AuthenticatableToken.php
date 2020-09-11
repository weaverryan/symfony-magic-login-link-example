<?php

namespace App\MagicLink;

class AuthenticatableToken
{
    private $selector;
    private $hashedVerifier;
    private $user;
    private $expiresAt;

    public function __construct(string $selector, string $hashedVerifier, object $user, \DateTimeInterface $expiresAt)
    {
        $this->selector = $selector;
        $this->hashedVerifier = $hashedVerifier;
        $this->user = $user;
        $this->expiresAt = $expiresAt;
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function getHashedVerifier(): string
    {
        return $this->hashedVerifier;
    }

    public function getUser(): object
    {
        return $this->user;
    }

    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt->getTimestamp() <= \time();
    }
}
