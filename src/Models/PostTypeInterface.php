<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

interface PostTypeInterface
{
    /**
     * Registers the post type.
     */
    public function register(): void;

    /**
     * Sets the post updated messages for the post type.
     *
     * @param array $messages post updated messages
     *
     * @return array messages for the `cptplaceholder` post type
     */
    public function updateMessages(array $messages): array;
}
