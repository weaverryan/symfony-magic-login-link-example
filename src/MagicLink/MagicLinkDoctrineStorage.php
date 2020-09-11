<?php

namespace App\MagicLink;

class MagicLinkDoctrineStorage
{
    public function storeMagicLinkToken(string $selector, string $hashedVerifier, object $user, \DateTimeInterface $expiresAt)
    {
            
    }
}
