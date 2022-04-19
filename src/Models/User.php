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
}
