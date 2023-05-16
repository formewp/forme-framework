<?php

declare(strict_types=1);

namespace Forme\Framework\Auth;

use DateTime;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Token implements TokenInterface
{
    public function get(string $name): ?string
    {
        $this->purge();
        $result = Capsule::table('forme_auth_tokens')->where('name', '=', $name)->first();
        if ($result === null) {
            $result = $this->create($name);
        }

        return $result->token;
    }

    public function validate(string $token, string $name): bool
    {
        $this->purge();
        $result = Capsule::table('forme_auth_tokens')->where('name', '=', $name)->first();

        return $result && $result->token === $token;
    }

    /**
     * @return Model|object|static|null
     */
    private function create(string $name)
    {
        $token  = Uuid::uuid4();
        $expiry = strtotime('+2 hours', time());
        $dt     = new DateTime();
        $dt->setTimestamp($expiry);

        $id = Capsule::table('forme_auth_tokens')->insertGetId(
            ['name' => $name, 'token' => $token, 'expiry' => $dt->format('Y-m-d H:i:s')]
        );

        return Capsule::table('forme_auth_tokens')->where('id', '=', $id)->first();
    }

    /**
     * Purge expired tokens.
     */
    private function purge(): void
    {
        $dt = new DateTime();
        Capsule::table('forme_auth_tokens')->where('expiry', '<=', $dt->format('Y-m-d H:i:s'))->delete();
    }
}
