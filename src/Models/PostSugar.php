<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

/**
 * @property int    $ID
 * @property int    $id
 * @property string $post_title
 * @property string $title
 * @property string $post_type
 * @property string $type
 * @property string $post_name
 * @property string $slug
 * @property string $url
 */
trait PostSugar
{
    public function getUrlAttribute(): string
    {
        return get_permalink($this->ID);
    }

    public function getTitleAttribute(): string
    {
        return $this->post_title;
    }

    public function getSlugAttribute(): string
    {
        return $this->post_name;
    }

    public function getTypeAttribute(): string
    {
        return $this->post_type;
    }

    public function getIdAttribute(): int
    {
        return $this->ID;
    }
}
