<?php

declare(strict_types=1);

namespace App\Service\Password\Hasher;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class DefaultPasswordHasher
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function hashPassword(
        #[\SensitiveParameter] string $password,
        PasswordAuthenticatedUserInterface $user,
    ): string {
       return $this->hasher->hashPassword($user, $password);
    }
}
