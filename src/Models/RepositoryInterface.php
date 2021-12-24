<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

/**
 * @deprecated Use Eloquent Models instead
 */
interface RepositoryInterface
{
    /**
     * get all posts of this type.
     **/
    public function all(): array;

    /**
     * get the post where $type is $value. If no post arr with the name $type will look for acf.
     **/
    public function get(string $value, string $type = 'p'): array;

    /**
     * add a new post with the (properly formatted) $data array
     * 'wp' contains wp stuff
     * 'ac' conttains acf fields
     * 'tg' contains tags.
     **/
    public function add(array $data): ?int;

    /**
     * update the post $id with the (properly formatted $data array).
     **/
    public function update(int $id, array $data): ?int;

    /**
     * delete the post $id.
     */
    public function delete(int $id): ?int;
}
