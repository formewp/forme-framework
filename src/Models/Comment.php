<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{
    protected $primaryKey = 'comment_ID';

    protected $table = 'comments';

    /**
     * Post relation for a comment.
     */
    public function post(): HasOne
    {
        return $this->hasOne(\Forme\Framework\Models\Post::class);
    }
}
