<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $meta_id
 * @property string $meta_key
 * @property string $meta_value
 * @property int    $post_id
 * @property Post   $post
 */
class PostMeta extends Model
{
    protected $primaryKey = 'meta_id';

    public $timestamps = false;

    protected $table = 'postmeta';

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'ID');
    }
}
