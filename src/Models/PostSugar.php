<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

/**
 * @property int    $ID
 * @property string $post_title
 * @property string $title
 * @property string $post_content
 * @property string $content
 * @property string $post_excerpt
 * @property string $excerpt
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

    public function getContentAttribute(): string
    {
        return $this->post_content;
    }

    public function setContentAttribute(string $value): void
    {
        $this->post_content = $value;
    }

    public function getExcerptAttribute(): string
    {
        return $this->post_excerpt;
    }

    public function setExcerptAttribute(string $value): void
    {
        $this->post_excerpt = $value;
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
