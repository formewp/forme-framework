<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $primaryKey = 'ID';

    public $timestamps = false;

    protected $table = 'users';

    /**
     * Relation for user meta.
     */
    public function meta(): HasMany
    {
        return $this->hasMany(UserMeta::class, 'user_id');
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
}
