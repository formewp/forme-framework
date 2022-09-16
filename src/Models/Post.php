<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int                        $ID
 * @property int                        $id
 * @property string                     $post_title
 * @property string                     $title
 * @property string                     $post_status
 * @property string                     $post_type
 * @property string                     $type
 * @property int                        $post_parent
 * @property string                     $post_name
 * @property string                     $slug
 * @property string                     $url
 * @property Collection<array-key,self> $children
 */
class Post extends Model
{
    use PostSugar;

    protected $table = 'posts';

    protected $primaryKey = 'ID';

    /**
     * @var string
     */
    public const CREATED_AT = 'post_date';

    /**
     * @var string
     */
    public const UPDATED_AT = 'post_modified';

    /**
     * Filter by post type.
     */
    public function scopeType(Builder $query, string $type = 'post'): Builder
    {
        return $query->where('post_type', '=', $type);
    }

    /**
     * Filter by post status.
     */
    public function scopeStatus(Builder $query, string $status = 'publish'): Builder
    {
        return $query->where('post_status', '=', $status);
    }

    /**
     * Filter by post author.
     */
    public function scopeAuthor(Builder $query, ?string $author = null): ?Builder
    {
        if ($author) {
            return $query->where('post_author', '=', $author);
        } else {
            return null;
        }
    }

    /**
     * Get comments from the post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'comment_post_ID');
    }

    /**
     * Get meta fields from the post.
     */
    public function meta(): HasMany
    {
        return $this->hasMany(PostMeta::class, 'post_id');
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        // if key ends with "_meta" return the meta value
        if (str_ends_with($key, '_meta')) {
            return $this->meta->firstWhere('meta_key', substr($key, 0, -4))?->meta_value;
        }

        return parent::__get($key);
    }

    public function generateSlug(): void
    {
        $slug = wp_unique_post_slug(
            sanitize_title($this->post_title),
            $this->ID,
            $this->post_status,
            $this->post_type,
            $this->post_parent
        );

        $this->post_name = $slug;
    }

    public function save(array $options = [])
    {
        if (!$this->post_name) {
            $this->generateSlug();
        }

        return parent::save($options);
    }

    /**
     * @return Collection<array-key,self>
     */
    public function getChildrenAttribute(): Collection
    {
        $children = get_pages([
            'parent'      => $this->ID,
            'post_type'   => $this->post_type,
            'post_status' => 'publish',
        ]);

        // turn this into a collection of models
        return collect($children)->map(function ($child) {
            return self::find($child->ID);
        });
    }

    public function hasChildren(): bool
    {
        return $this->children->isNotEmpty();
    }
}
