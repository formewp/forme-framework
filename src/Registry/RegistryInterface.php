<?php
declare(strict_types=1);

namespace Forme\Framework\Registry;

interface RegistryInterface
{
    // implement this interface for registries
    public function register(): void;

    // this method should perform the actions attached to a WP hook
    // for example enqueue styles/scripts, add shortcodes, registering api routes
}
