<?php
declare(strict_types=1);

namespace Forme\Framework\Auth;

interface TokenInterface
{
    public function get(string $name): ?string;

    public function validate(string $token, string $name): bool;
}
