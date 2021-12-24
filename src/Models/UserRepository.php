<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

/**
 * @deprecated Use Eloquent Models instead
 */
class UserRepository implements RepositoryInterface
{
    /** @var string */
    protected $postType;

    public function all(): array
    {
        $args = ['fields' => 'all_with_meta'];

        return get_users($args);
    }

    /**
     * @param string|int $value
     */
    public function get($value, string $type = 'p'): array
    {
        // convert "p" to "id"
        $type = $type === 'p' ? 'id' : $type;
        // if type is a valid post arr argument then look via that
        if ($this->isValidPostArr($type)) {
            // this will be a single user so wrap in array for consistency
            $result = get_user_by($type, $value);

            return $result ? [$result] : $result;
        } else {
            // otherwise get acf/meta
            return $this->getByMeta($value, $type);
        }
        // TODO: Add get by role type
    }

    protected function getbyMeta(string $value, string $type): array
    {
        $args = [
            'number'     => -1,
            'meta_key'   => $type,
            'meta_value' => $value,
        ];

        return get_users($args);
    }

    public function add(array $data): ?int
    {
        // wp_insert_user
        // update_field
        if (!empty($data['wp'])) {
            $userData = $data['wp'];
            $id       = wp_insert_user($userData);
            // convert wp error to false
            $id = is_wp_error($id) ? false : $id;
        }
        if (!empty($data['ac']) && $id) {
            $this->updateAcfFields($id, $data['ac']);
        }
        // TODO add groups
        return $id ?? null;
    }

    public function update(int $id, array $data): ?int
    {
        // wp_update_user
        // update_field
        if (!empty($data['wp'])) {
            // merge post data
            $userData = array_merge(['ID' => $id], $data['wp']);
            wp_update_user($userData);
            // convert wp error to false
            $id = is_wp_error($id) ? null : $id;
        }
        if (!empty($data['ac']) && $id) {
            $this->updateAcfFields($id, $data['ac']);
        }
        // TODO: handle groups
        return $id;
    }

    public function delete(int $id): ?int
    {
        // wp_delete_user
        return wp_delete_user($id) ? $id : null;
    }

    protected function updateAcfFields(int $id, array $fields): void
    {
        foreach ($fields as $selector => $value) {
            update_field($selector, $value, 'user_' . $id);
        }
    }

    protected function isValidPostArr(string $argName): bool
    {
        // check if valid post arr argument
        $postArrArgs = [
            'id',
            'slug',
            'email',
            'login',
        ];

        return in_array($argName, $postArrArgs);
    }
}
