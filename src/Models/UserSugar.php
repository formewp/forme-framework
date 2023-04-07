<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

/**
 * @property int    $ID
 * @property string $user_login
 * @property string $login
 * @property string $user_nicename
 * @property string $nicename
 * @property string $user_email
 * @property string $email
 * @property string $user_url
 * @property string $url
 * @property string $user_registered
 * @property string $user_activation_key
 * @property string $activation_key
 * @property int    $user_status
 * @property string $display_name
 */
trait UserSugar
{
    public function getUrlAttribute(): string
    {
        return $this->user_url;
    }

    public function setUrlAttribute(string $value): void
    {
        $this->user_url = $value;
    }

    public function getLoginAttribute(): string
    {
        return $this->user_login;
    }

    public function setLoginAttribute(string $value): void
    {
        $this->user_login = $value;
    }

    public function getNicenameAttribute(): string
    {
        return $this->user_nicename;
    }

    public function setNicenameAttribute(string $value): void
    {
        $this->user_nicename = $value;
    }

    public function getEmailAttribute(): string
    {
        return $this->user_email;
    }

    public function setEmailAttribute(string $value): void
    {
        $this->user_email = $value;
    }

    public function getActivationKeyAttribute(): string
    {
        return $this->user_activation_key;
    }

    public function setActivationKeyAttribute(string $value): void
    {
        $this->user_activation_key = $value;
    }
}
