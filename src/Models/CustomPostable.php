<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Builder;
use function Symfony\Component\String\u;

/**
 * @property string $postType
 */
trait CustomPostable
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        if (isset(self::$postType)) {
            // if posttype is set we use that
            $postType = self::$postType;
        } else {
            $explode = explode('\\', static::class);
            // otherwise try snake case version of the class name
            $postType = u(end($explode))->snake()->toString();
        }

        static::addGlobalScope('postType', fn (Builder $query) => $query->where('post_type', '=', $postType));
    }
}
