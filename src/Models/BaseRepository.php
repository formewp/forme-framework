<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

/**
 * @deprecated Use Eloquent Models instead
 */
class BaseRepository implements RepositoryInterface
{
    /** @var string */
    protected $postType;

    public function all(): array
    {
        $args = [
            'post_type'      => $this->postType,
            'posts_per_page' => -1,
        ];

        return get_posts($args);
    }

    public function get(string $value, string $type = 'p'): array
    {
        // convert "id" to "p" for sanity's sake
        $type = $type === 'id' ? 'p' : $type;
        // if type is a valid post arr argument then look via that
        if ($this->isValidPostArr($type)) {
            $args = [
                'post_type'      => $this->postType,
                'posts_per_page' => -1,
                'post_status'    => ['publish', 'pending', 'draft'],
                $type            => $value,
            ];

            return get_posts($args);
        } else {
            // otherwise get acf/meta
            return $this->getByMeta($value, $type);
        }
    }

    protected function getbyMeta(string $value, string $type): array
    {
        $args = [
            'post_type'      => $this->postType,
            'posts_per_page' => -1,
            'meta_key'       => $type,
            'meta_value'     => $value,
            'post_status'    => ['publish', 'pending', 'draft'],
        ];

        return get_posts($args);
    }

    public function add(array $data): ?int
    {
        $id = null;
        // wp_insert_post
        // update_field
        if (!empty($data['wp'])) {
            $postData = array_merge(['post_type' => $this->postType], $data['wp']);
            $id       = wp_insert_post($postData);
        }

        if (!empty($data['ac']) && $id) {
            $this->updateAcfFields($id, $data['ac']);
        }

        if (!empty($data['tg']) && $id) {
            $this->updateTaxonomies($id, $data['tg']);
        }

        return $id ?? null;
    }

    public function update(int $id, array $data): ?int
    {
        // wp_update_post
        // update_field
        if (!$this->isModel($id)) {
            return null;
        }

        if (!empty($data['wp'])) {
            // merge post data
            $postData = array_merge(['ID' => $id], $data['wp']);
            wp_update_post($postData);
        }

        if (!empty($data['ac'])) {
            $this->updateAcfFields($id, $data['ac']);
        }

        if (!empty($data['tg'])) {
            $this->updateTaxonomies($id, $data['tg']);
        }

        return $id;
    }

    public function delete(int $id): ?int
    {
        // wp_delete_post
        if (!$this->isModel($id)) {
            return null;
        }

        return wp_delete_post($id) ? $id : null;
    }

    protected function isModel(int $id): bool
    {
        // check if this is the right model/post type
        return get_post_type($id) == $this->postType;
    }

    protected function updateAcfFields(int $id, array $fields): void
    {
        foreach ($fields as $selector => $value) {
            update_field($selector, $value, $id);
        }
    }

    protected function updateTaxonomies(int $id, array $fields): void
    {
        foreach ($fields as $taxonomy => $value) {
            wp_set_object_terms($id, $value, $taxonomy);
        }
    }

    protected function isValidPostArr(string $argName): bool
    {
        // check if valid post arr argument
        $postArrArgs = [
            'attachment_id',
            'author__in',
            'author__not_in',
            'author_name',
            'author',
            'cache_results',
            'cat',
            'category__and',
            'category__in',
            'category__not_in',
            'category_name',
            'category',
            'comment_count',
            'comment_status',
            'comments_per_page',
            'date_query',
            'day',
            'exact',
            'exclude',
            'fields',
            'hour',
            'ignore_sticky_posts',
            'include',
            'lazy_load_term_meta',
            'm',
            'menu_order',
            'meta_compare_key',
            'meta_compare',
            'meta_key',
            'meta_query',
            'meta_type_key',
            'meta_value_num',
            'meta_value',
            'monthnum',
            'name',
            'no_found_rows',
            'nopaging',
            'numberposts',
            'offset',
            'order',
            'orderby',
            'p',
            'page_id',
            'page',
            'paged',
            'pagename',
            'perm',
            'ping_status',
            'post__in',
            'post__not_in',
            'post_mime_type',
            'post_name__in',
            'post_parent__in',
            'post_parent__not_in',
            'post_parent',
            'post_status',
            'post_type',
            'posts_per_archive_page',
            'posts_per_page',
            's',
            'second',
            'sentence',
            'suppress_filters',
            'suppress_filters',
            'tag__and',
            'tag__in',
            'tag__not_in',
            'tag_id',
            'tag_slug__and',
            'tag_slug__in',
            'tag',
            'tax_query',
            'title',
            'update_post_meta_cache',
            'update_post_term_cache',
            'w',
            'year',
        ];

        return in_array($argName, $postArrArgs);
    }
}
