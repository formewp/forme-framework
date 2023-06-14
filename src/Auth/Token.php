<?php

declare(strict_types=1);

namespace Forme\Framework\Auth;

use DateTime;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Token implements TokenInterface
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    public function get(string $name, string $expire = '+2 hours'): ?string
    {
        $result = $this->getFromDatabase($name);
        if ($result === null) {
            $result = $this->create($name, $expire);
        }

        return $result->token;
    }

    public function validate(string $token, string $name): bool
    {
        $result = $this->getFromDatabase($name);

        return $result && $result->token === $token;
    }

    public function destroy(string $name): void
    {
        Capsule::table('forme_auth_tokens')
            ->where('name', '=', $name)
            ->where('deleted_at', '=', null)
            ->update(['deleted_at' => (new DateTime())->format(self::DATE_FORMAT)]);
    }

    public function expires(string $name): ?DateTime
    {
        $result = $this->getFromDatabase($name);

        return $result ? new DateTime($result->expiry) : null;
    }

    /**
     * @return Model|object|static|null
     */
    private function getFromDatabase(string $name)
    {
        $this->purge();

        return Capsule::table('forme_auth_tokens')
            ->where('name', '=', $name)
            ->where('deleted_at', '=', null)
            ->first();
    }

    /**
     * @return Model|object|static|null
     */
    private function create(string $name, string $expire)
    {
        $token  = Uuid::uuid4();
        $expiry = strtotime($expire, time());
        $dt     = new DateTime();
        $dt->setTimestamp($expiry);

        $id = Capsule::table('forme_auth_tokens')->insertGetId(
            ['name' => $name, 'token' => $token, 'expiry' => $dt->format(self::DATE_FORMAT)]
        );

        return Capsule::table('forme_auth_tokens')
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Purge expired tokens.
     */
    private function purge(): void
    {
        $dt = new DateTime();
        Capsule::table('forme_auth_tokens')
            ->where('expiry', '<=', $dt->format(self::DATE_FORMAT))
            ->where('deleted_at', '=', null)
            ->update(['deleted_at' => $dt->format(self::DATE_FORMAT)]);
    }
}
