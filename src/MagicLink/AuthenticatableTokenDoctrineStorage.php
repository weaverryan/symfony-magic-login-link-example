<?php

namespace App\MagicLink;

class AuthenticatableTokenDoctrineStorage
{
    public function storeToken(string $selector, string $hashedVerifier, object $user, \DateTimeInterface $expiresAt)
    {

    }
}
