<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Builder;
use function Symfony\Component\String\u;

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
            // otherwise try snake case version of the class name
            $postType = u(end(explode('\\', get_called_class())))->snake()->toString();
        }

        static::addGlobalScope('postType', function (Builder $query) use ($postType) {
            return $query->where('post_type', '=', $postType);
        });
    }
}
