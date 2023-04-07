<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

/**
 * @property int    $ID
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

    public function setTitleAttribute(string $value): void
    {
        $this->post_title = $value;
    }

    public function getSlugAttribute(): string
    {
        return $this->post_name;
    }

    public function setSlugAttribute(string $value): void
    {
        $this->post_name = $value;
    }

    public function getTypeAttribute(): string
    {
        return $this->post_type;
    }
}
